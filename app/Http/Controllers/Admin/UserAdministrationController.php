<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Auth;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Validator;
use Illuminate\Http\Request;
use Mail;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Support\Collection as Collection;


class UserAdministrationController extends Controller
{
    protected $repository;
    protected $em;

    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }

    public function userAdministration()
    {
        $loggedUser = Auth::user();
        //$users = $this->repository->findBy(['god' => false]);
        $users = $this->paginate($this->repository->findAll(),15);
        //$users = DB::table('User')->paginate(15);
       	//dd($users);
        if($loggedUser->getGod())
        {
            return view('god.users')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => $users
            ]);
        } else {
            return redirect('/');
        }
    }

    public function newUser()
    {
        $loggedUser = Auth::user();
        if($loggedUser->getGod())
        {
            return view('god.userNew')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
            ]);
        } else {
            return redirect('/');
        }
    }

    public function register(Request $request)
    {
        $loggedUser = Auth::user();
        if($loggedUser->getGod())
        {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                    );
            }
            //Auth::login($this->create($request->all()));
            $activationCode = $this->sendEmailReminder($request, 0);
            $this->create($request->all(),$activationCode);

            //return redirect(route('adminUser'));
            return redirect('/admin/user');
        } else {
            return redirect('/');
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:User',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function sendEmailReminder(Request $request, $id)
    {
        //$user = User::findOrFail($id);
        //$loggedUser = Auth::user();
        $loggedUser = $request['email'];
        //$generatedKey = "http://localhost:8000/activation/". sha1(mt_rand(1000000,9999999).time().$loggedUser);
        $key = sha1(mt_rand(1000000,9999999).time().$loggedUser);
        $generatedKey = env('WEB_HOST', 'http://localhost:8800/activation/') . $key;
        Mail::send('activation', ['activationCode' => $generatedKey], function ($m) use ($loggedUser) {
        	$m->from($loggedUser, 'Activation Email');
            //$m->from($loggedUser->getEmail(), 'Activation Email');
            $m->to('euskalvirus@gmail.com', 'alain')->subject('Activation Email!');
            //$m->to('azabaleta@barnetik.com', 'alain')->subject('Activation Email!');
        });
        //var_dump($request);exit;
        return $key;
    }

    public function editUser($id)
    {
        $loggedUser = Auth::user();

        if($loggedUser->getGod() || $loggedUser->getId()==$id)
        {
        	$user = $this->repository->find($id);
        	$filteredGroups=$this->getUserFilteredGroups($id, $user);
            return view('god.userEdit')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'data' => $user,
    			'groups' => $filteredGroups
            ]);
        } else {
            return redirect('/');
        	//return redirect()->back();
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    private function create(array $data, $activationCode)
    {
    	$user = new User();
    	$user->setName($data['name']);
    	$user->setEmail($data['email']);
    	$user->setPassword(bcrypt($data['password']));
    	$user->setGod(false);
    	$user->setUserActive(false);
    	$user->setActivationCode($activationCode);
    	$user->setCreatedAt($mysqltime = date("Y-m-d H:i:s"));
    	$user->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
    	$key = KeyFactory::generateEncryptionKey();
    	KeyFactory::save($key, storage_path() . '/' . 'encryption.key');
    	$salt = file_get_contents(storage_path() . '/' . 'encryption.key');
    	unlink(storage_path() . '/' . 'encryption.key');
    	$user->setSalt($salt);
    	$this->em->persist($user);
        $this->em->flush();

    	return $user;
    }

    private function idToPath($id) {
    	if ($id < 10) {
    		return "0/" . $id;
    	}
    	$idArray = str_split((string)$id);
    	array_pop($idArray);
    	return implode('/', $idArray);
    }

    protected function userDelete($id)
    {
    	$loggedUser = Auth::user();
    	//$user = $this->repository->find($id);
    	$user = $this->em->find("Behigorri\Entities\User",$id);
    	if($loggedUser->getGod() && $loggedUser->getId()!=$id && $user)
    	{
    		$groups = $user->getGroups();
    		foreach ($groups as $group)
    		{
    			$user->removeGroup($group);
    		}
    		$datas = $user->getSensitiveDatas();
    		foreach ($datas as $data)
    		{
    			$dataGroups = $data->getGroups();
    			foreach ($dataGroups as $dGroup)
    			{
    				$data->removeGroup($dGroup);
    			}
    			$user->removeSensitiveData($data);
    			//HAY QUE VER SI TE TIENE QUE ELIMINAR LOS SENSITIVEDATAS EN PROPIEDAD DEL USUARIO O DARLES NUEVOS DUEÑOS
    			$dataId = $data->getId();
    			$this->path = storage_path() . '/' . $this->idToPath($dataId);
    			$this->filePath = $this->path . '/' . substr($dataId,-1);

    			if (file_exists($this->filePath)) {
    				unlink($this->filePath);
    			}
    			$this->em->remove($data);
    		}
    		$this->em->remove($user);
            $this->em->flush();
    	}
    	return redirect('/admin/user');
    	//return redirect()->back();

    }

    protected function activeUser($activationcode){

    	$user = $this->repository->findBy(['activationCode' => $activationcode]);
    	//var_dump($user);exit;
    	if($user==null)
    	{
    		return redirect('/');
    	}
    	if(!$user[0]->getUserActive()){
    		$user[0]->setUserActive(true);
    		$this->em->persist($user[0]);
    		$this->em->flush();
    		return view('user.userActivation')->with([
    				'user' => $user[0]->getEmail(),
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'data' => 'User account activation done'
    		]);

    	}else{
    		return view('user.userActivation')->with([
    				'user' => '',
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'data' => 'User account has been activated before'
    		]);

    	}
    }

    protected function viewUser($id)
    {
    	$loggedUser = Auth::user();
    	if($loggedUser->getGod())
    	{
    		$user = $this->repository->find($id);
    		$filteredGroups=$this->getUserFilteredGroups($id, $user);

    		return view('god.userView')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'data' => $user,
    				'groups' => $filteredGroups
    		]);
    	}else {
    		return redirect('/');
    		//return redirect()->back();
    	}
    }

    private function getUserFilteredGroups($id, $user)
    {
    	$filteredGroups=[];
    	$groups = $this->em->getRepository('Behigorri\Entities\Group')->findAll();
    	$userGroups =  $user->getGroups();
    	foreach($userGroups as $group)
    	{
    		$filteredGroups[$group->getId()]=[
    				'name' => $group->getName(),
    				'active' => true
    		];
    	}
    	foreach($groups as $group)
    	{
    		if (!isset($filteredGroups[$group->getId()])){
    			$filteredGroups[$group->getId()]=[
    					'name' => $group->getName(),
    					'active' => false
    			];
    		}
    	}
    	return $filteredGroups;
    }

    protected  function  userUpdate(Request $request)
    {
    	$loggedUser = Auth::user();
    	$user = $this->repository->find($request->input('id'));
    	if($loggedUser->getGod() || $loggedUser->getId()== $user->getId() )
    	{
    		$newGroups=$request->input('groups', []);
    		foreach($user->getGroups() as $group)
    		{
    			if(in_array($group->getId(),$newGroups)){
    				unset($newGroups[array_search($group->getId(), $newGroups)]);
    			} else {
    				$user->removeGroup($group);
    			}
    		}

    		foreach($newGroups as $groupId)
    		{
    			$group= $this->em->find("Behigorri\Entities\Group", $groupId);
    			$user->addGroup($group);
    		}
    		$user->setName($request->input('name'));
    		$user->setEmail($request->input('email'));
    		$this->em->persist($user);
    		$this->em->flush();
    		return redirect('/admin/user');
    	}

    }

    protected  function  userPasswordUpdate(Request $request)
    {
   		$loggedUser = Auth::user();
    	$user = $this->em->find("Behigorri\Entities\User",$request->input('id'));
    	if($loggedUser->getGod() || $loggedUser->getId()== $user->getId())
    	{
    		$pass=$request['password_confirmation'];
    		$passConfirm=$request['password_confirmation'];
    		if($pass==$passConfirm){
    			$user->setPassword(bcrypt($pass));
    			$this->em->persist($user);
    			$this->em->flush();
    			return redirect('/admin/user');
    		}

    	}

    }

    protected function editProfile()
    {
    	$loggedUser = Auth::user();
    	return $this->editUser($loggedUser->getId());
    }
     protected function generateSalt()
     {
     	$loggedUser = Auth::user();
     	if($loggedUser->getGod())
     	{
     		$key = KeyFactory::generateEncryptionKey();
     		KeyFactory::save($key, storage_path() . '/' . 'encryption.key');
     		$salt = file_get_contents(storage_path() . '/' . 'encryption.key');
     		unlink(storage_path() . '/' . 'encryption.key');
     		$loggedUser->setSalt($salt);
     		$this->em->persist($loggedUser);
     		$this->em->flush();
     	}
     	return redirect('/');
     }

     public function userSearch(Request $request)
     {
     	$loggedUser = Auth::user();
     	if($loggedUser->getGod())
     	{
     		$search['name'] = $request->input('search');
    		$result = $this->repository->search($search);
    		$users = $this->paginate($result,15);
    		//dd($users);
    		return view('god.users')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'datas' => $users
    		]);

     	} else {
     		return redirect('/');
     	}
     }
     public function paginate($items,$perPage)
     {
     	$pageStart = \Request::get('page', 1);
     	// Start displaying items from this number;
     	$offSet = ($pageStart * $perPage) - $perPage;

     	// Get only the items you need using array_slice
     	$itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

     	return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
     }


}
