<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Behigorri\Entities\Group;
use Auth;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Validator;
use Illuminate\Http\Request;
use Mail;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Collection as Collection;
use Config;
use Crypt;
use ParagonIE\Halite\File;
use Illuminate\Http\Response;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

class UserAdministrationController extends Controller
{
    protected $repository;
    protected $em;

    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        //$this->repository = $em->getRepository('Behigorri\Entities\User');
        $this->repository = $this->em->getRepository(User::class);
    }

    public function userAdministration()
    {
        $loggedUser = Auth::user();
        $users = $this->repository->paginate($this->repository->findAll());
        //$users = $this->paginate($this->repository->findAll(),15);
        //$users = DB::table('User')->paginate(15);
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
                'groups' => $this->em->getRepository(Group::class)->findAll()
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
            $request['password'] = str_random(12);
            $request['decryptPassword'] = str_random(12);
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                    );
            }
            //Auth::login($this->create($request->all()));
            $activationCode = $this->sendEmailReminder($request, 0);
            $user = $this->create($request->all(),$activationCode);
            foreach((array)$request['groups'] as $groupId)
    		    {
    			         $group= $this->em->find("Behigorri\Entities\Group", $groupId);
    			            $user->addGroup($group);
    		    }
            $this->em->persist($user);
            $this->em->flush();

            //return redirect(route('adminUser'));
            return redirect('/admin/user');
        } else {
            return redirect('/');
        }
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:User',
            //'password' => 'required|confirmed|min:6',
            //'decryptPassword' => 'required|confirmed|min:6',
        ], $this->repository->getValitationMessages());
    }

    private function sendEmailReminder(Request $request, $id)
    {

        $loggedUser = Auth::user();
        $userName = $request['name'];
        $userEmail = $request['email'];
        $pass = $request['password'];
        $dPass = $request['decryptPassword'];
        //$generatedKey = "http://localhost:8000/activation/". sha1(mt_rand(1000000,9999999).time().$loggedUser);
        $key = sha1(mt_rand(1000000,9999999).time().$userEmail);
        $generatedKey =  Config::get('app.url') . ':8000/activation/' . $key;
        Mail::send('activation', ['activationCode' => $generatedKey, 'password' => $pass, 'decryptPassword' => $dPass], function ($m) use ($loggedUser, $userName, $userEmail) {
          $m->from($loggedUser->getEmail(),'Behigorri Password Manager');
          $m->to($userEmail, $userName)->subject('Activation Email!');
            //$m->to('euskalvirus@gmail.com', 'alain')->subject('Activation Email!');
            //$m->to($userEmail, $userName)->subject('Activation Email!');
        });
        return $key;
    }

    public function editUser($id)
    {
        $loggedUser = Auth::user();
        $user = $this->repository->find($id);
        if(($loggedUser->getGod() || $loggedUser->getId()==$id) && $user)
        {

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
    	$user->setName($this->repository->avoidSqlInjection($data['name']));
    	$user->setEmail($data['email']);
    	$user->setPassword(bcrypt($data['password']));
    	$user->setGod(false);
    	$user->setUserActive(false);
    	$user->setActivationCode($activationCode);
    	$user->setCreatedAt($mysqltime = date("Y-m-d H:i:s"));
    	$user->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
        $salt = $this->repository->saltGenerator();
    	$user->setSalt($salt);
      $user->setDecryptPassword(bcrypt($data['decryptPassword'] . $salt));
    	return $user;
    }

    private function idToPath($id) {
        if ($id < 10) {
          $this->fileName = $id;
            return "0/" . $id;
        }
        $idArray = str_split((string)$id);
        $this->fileName = end($idArray);
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
    			$user->removeSensitiveData($data);
    			//HAY QUE VER SI TE TIENE QUE ELIMINAR LOS SENSITIVEDATAS EN PROPIEDAD DEL USUARIO O DARLES NUEVOS DUEÑOS
    			$dataId = $data->getId();
    			$this->path = storage_path() . '/' . $this->idToPath($dataId);
    			$this->filePath = $this->path . '/' . substr($dataId,-1);
                if($data->getGroup()===null)
                {
                    if (file_exists($this->filePath)) {
        				unlink($this->filePath);
        			}
                    if($data->getHasFIle()  && file_exists($this->filePath .'.0'))
                    {
                        unlink($this->filePath . '.0');
                    }
                    $this->em->remove($data);
                }

    		}
    		$this->em->remove($user);
            $this->em->flush();
    	}
    	return redirect('/admin/user');
    	//return redirect()->back();

    }

    protected function activeUser($activationcode)
    {
    	$user = $this->repository->findBy(['activationCode' => $activationcode]);
    	if($user==null)
    	{
    		return redirect('/');
    	}
    	if(!$user[0]->getUserActive()){
    		$user[0]->setUserActive(true);
            $user[0]->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
    		$this->em->persist($user[0]);
    		$this->em->flush();
    		return view('auth.login')->with([
    				'user' => $user[0]->getEmail(),
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
            'data' => trans('translations.activationok')
    		]);
    	}else{
    		return view('auth.login')->with([
    				'user' => '',
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
            'data' => trans('translations.activationbefore')
    		]);

    	}
    }

    protected function viewUser($id)
    {
    	$loggedUser = Auth::user();
        $user = $this->repository->find($id);
    	if($loggedUser->getGod() && $user)
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
            $validator = $this->infoChangeValidator($request->all(),$user->getEmail());
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
    		$newGroups=$request->input('groups', []);
    		foreach($user->getGroups() as $group)
    		{
    			if(in_array($group->getId(),$newGroups)){
    				unset($newGroups[array_search($group->getId(), $newGroups)]);
    			} else {
                    $datas = $this->em->getRepository('Behigorri\Entities\SensitiveData')->findBy(['user' =>$user->getId(),'group' => $group->getId()]);
                    foreach($datas as $data)
                    {
                        $data->setUser(null);
                    }
    				$user->removeGroup($group);
    			}
    		}
    		foreach($newGroups as $groupId)
    		{
    			$group= $this->em->find("Behigorri\Entities\Group", $groupId);
    			$user->addGroup($group);
    		}
    		$user->setName($this->repository->avoidSqlInjection($request->input('name')));
            $user->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
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
            $validator = $this->passwordChangeValidator($request->all());
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }
    			$user->setPassword(bcrypt($request->input('password')));
          $user->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
    			$this->em->persist($user);
    			$this->em->flush();
          if($loggedUser->getGod() && $loggedUser->getId()!= $user->getId())
          {
            $this->sendPasswordUpdateReminder($loggedUser,$user,$request->input('password'));
          }
    			return redirect('/admin/user');
    	}
      return redirect()->back();
    }

    private function sendPasswordUpdateReminder($loggedUser,$user,$pass)
    {
        $loggedUser = Auth::user();
        $userName = $user->getName();
        $userEmail = $user->getEmail();
        Mail::send('passwordUpdate', ['pass' => $pass], function ($m) use ($loggedUser, $userName, $userEmail) {
          $m->from($loggedUser->getEmail(),'Behigorri Password Manager');
          $m->to($userEmail, $userName)->subject('Password Update!');
            //$m->to('euskalvirus@gmail.com', $userName)->subject('Password Update!');
        });
    }

    protected function editProfile()
    {
    	$loggedUser = Auth::user();
    	return $this->editUser($loggedUser->getId());
    }
     protected function generateSalt()
     {
     	$loggedUser = Auth::user();
     	if($loggedUser->getGod() || !$loggedUser->getSalt())
     	{
     		$salt = $this->repository->saltGenerator();
     		$loggedUser->setSalt($salt);
        $loggedUser->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
        if($loggedUser->getSensitiveDatas())
        {
          foreach($loggedUser->getSensitiveDatas() as $data)
          {
            if(!$data->getGroup())
            {
              $loggedUser->removeSensitiveData($data);
        			$dataId = $data->getId();
        			$this->path = storage_path() . '/' . $this->idToPath($dataId);
        			$this->filePath = $this->path . '/' . substr($dataId,-1);
                        if (file_exists($this->filePath)) {
            				unlink($this->filePath);
            			}
                        if($data->getHasFIle()  && file_exists($this->filePath .'.0'))
                        {
                            unlink($this->filePath . '.0');
                        }
                        $this->em->remove($data);
        		}else{
              $data->setUser(null);
            }

            }
          }
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
    		$users = $this->repository->paginate($result);
    		return view('god.users')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'datas' => $users
    		]);

     	} else {
     		return redirect('/');
     	}
     }

     private function infoChangeValidator(array $data, $previousEmail)
     {
        return Validator::make($data, [
            'name' => 'required|max:255'
        ], $this->repository->getValitationMessages());
     }

     private function passwordChangeValidator(array $data)
     {
         return Validator::make($data, [
             'password' => 'required|confirmed|min:12|max:255|alpha_num',
             'password_confirmation' => 'required'
             //'password_confirmation' => 'required|same:password'
         ], $this->repository->getValitationMessages());
     }

     public function userDecryptPasswordUpdate(Request $request)
     {
       $loggedUser = Auth::user();
     	if($loggedUser->getId()== $request->input('id'))
     	{
        if(!password_verify($request['password'], $loggedUser->getDecryptPassword()))
        {
          return redirect()->back()->withErrors(array('error' => 'incorrect user encrypt password'));
        }
        $validator = $this->decryptPasswordChangeValidator($request->all());
        if ($validator->fails()) {
          $this->throwValidationException(
              $request, $validator
          );
        }
        $salt = $loggedUser->getSalt();
        $newEncryptionKey = KeyFactory::deriveEncryptionKey($request['decryptpassword'], $salt);
        $oldEncryptionKey = KeyFactory::deriveEncryptionKey($request['password'], $salt);
        foreach($loggedUser->getSensitiveDatas() as $data)
        {
          if($data->getGroup() === null)
          {
           $this->repository->encryptSensitiveData($data, $oldEncryptionKey, $newEncryptionKey,null,null);
          }
        }
     		$pass=$request['decryptpassword_confirmation'];
     		$passConfirm=$request['decryptpassword_confirmation'];
   			$loggedUser->setDecryptPassword(bcrypt($pass));
        $loggedUser->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
     		$this->em->persist($loggedUser);
     		$this->em->flush();
     		return redirect('/admin/user');
     }
   }

   private function setPaths($id){
       $this->path = storage_path() . '/' . $this->idToPath($id);
       $this->filePath = $this->path . '/' . substr($id,-1);
   }

     private function decryptPasswordChangeValidator(array $data)
     {
         return Validator::make($data, [
             'decryptpassword' => 'required|confirmed|min:12',
             'decryptpassword_confirmation' => 'required'
         ], $this->repository->getValitationMessages());
     }
}
