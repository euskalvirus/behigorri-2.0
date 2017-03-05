<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Behigorri\Entities\Group;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Collection as Collection;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use ParagonIE\Halite\EncryptionKeyPair;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

class GroupAdministrationController extends Controller
{
    protected $repository;
    protected $em;
    protected $path;
    protected $filePath;
    protected $fileName;

    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        $this->repository = $em->getRepository('Behigorri\Entities\Group');
    }

    public function groupAdministration()
    {
        $loggedUser = Auth::user();
        $groups = $this->repository->paginate($this->repository->findAll());
        if($loggedUser->getGod())
        {
            return view('god.groups')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => $groups
            ]);
        } else {
            return redirect('/');
        }
    }

    public function newGroup()
    {
    	$loggedUser = Auth::user();
    	if($loggedUser->getGod())
    	{
        $users = $this->em->getRepository('Behigorri\Entities\User');
        $data = $users->findAll();
    		return view('god.groupNew')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'users' => $data
    		]);
    	} else {
    		return redirect('/');
    	}
    }

    public function groupSave(Request $request)
    {
      $loggedUser = Auth::user();
      if($loggedUser->getGod())
      {
    	   $validator = $this->validator($request->all(),'');

    	    if ($validator->fails()) {
    		      $this->throwValidationException(
    				        $request, $validator
    			    );
    	    }
    	  $group = new Group();
    	  $group->setName($this->repository->avoidSqlInjection($request->input('name')));
        $group->setCreatedAt($mysqltime = date("Y-m-d H:i:s"));
        $group->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
    	  $users=$request->input('users');
        $password =$request->input('password');
    		if($users == null){
    		    $users = [];
    	     }
    	  foreach ($users as $userId)
    	  {
    		    $user= $this->em->find("Behigorri\Entities\user", $userId);
    		    $user->addGroup($group);
    	  }

        $salt = \Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_KEYBYTES);
        $salt2= $this->repository->saltGenerator();
        //to has password with salt and create a difficult way to know the password
        $salt3 = hash("sha256", $password . $salt);
        $keyFactoryFile  = fopen(storage_path() . '/group.key', "w") or die("Unable to open file!");
        fwrite($keyFactoryFile  , $salt);
        fclose($keyFactoryFile );
        $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt2);
        $group->setDecryptPassword(bcrypt($password));
        $group->setSalt($salt);
    	  $this->em->persist($group);
    	  $this->em->flush();
    	  return redirect('/admin/group');
     }else{
      return redirect('/');
     }

    }
    private function validator(array $data, $groupName)
    {
        if(!empty($groupName) && $data['name'] !== $groupName)
        {
    	       return Validator::make($data, [
    			            'name' => 'required|max:255|unique:Group'
    	              ], $this->repository->getValitationMessages());
        } elseif(empty($groupName)){
            return Validator::make($data, [
                         'name' => 'required|max:255|unique:Group',
                         'password' => 'required|min:6',
                   ], $this->repository->getValitationMessages());
        }else{
          return Validator::make($data, [
                       'name' => 'required|max:255'
                 ], $this->repository->getValitationMessages());
        }
    }

    protected function groupDelete($id)
    {
    	$loggedUser = Auth::user();
    	$group = $this->repository->find($id);
    	if($loggedUser->getGod() && $group)
    	{
    		$users = $group->getUsers();
    		foreach ($users as $user)
    		{
    			$group->removeUser($user);
    		}
        $datas = $group->getSensitiveDatas();
        //THIS WAY ALL SENSITIVEDATAS SAHRED ON THE GROUP ARE DELETED
        foreach ($datas as $data)
    		{
          $this->sensitiveDataDelete($data);
    			$data->setGroup(null);
    		}
        $this->em->persist($group);
    		$this->em->remove($group);
    		$this->em->flush();
    	}
    	return redirect('/admin/group');

    }
    private function sensitiveDataDelete($data)
    {
        $this->setPaths($data->getId());
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
        if($data->getHasFIle() && file_exists($this->filePath .'.0'))
        {
            unlink($this->filePath .'.0');
        }
        $this->em->remove($data);
        $this->em->flush();
    }
    private function setPaths($id){
        $this->path = storage_path() . '/' . $this->idToPath($id);
        $this->filePath = $this->path . '/' . substr($id,-1);
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

    protected function groupEdit($id)
    {
    	$loggedUser = Auth::user();
    	$group = $this->repository->find($id);

    	if($loggedUser->getGod() && $group)
    	{
    		$groupUsers = $group->getUsers();
    		$allUsers = $this->em->getRepository('Behigorri\Entities\User')->findAll();
    		$filteredUsers=[];
    		foreach($groupUsers as $user)
    		{
    			$filteredUsers[$user->getId()] = [
    					'name' => $user->getName(),
    					'active' => true
    			];
    		}
    		foreach ($allUsers as $user)
    		{
    			if(!isset($filteredUsers[$user->getId()]))
    			{
    				$filteredUsers[$user->getId()] = [
    						'name' => $user->getName(),
    						'active' => false
    				];

    			}
    		}
    		return view('god.groupEdit')->with([
            	'user' => $loggedUser,
            	'title' => 'BEHIGORRI PASSWORD MANAGER',
            	'group' => $group,
    			'users' => $filteredUsers
            ]);
    	}
    	return redirect('/');

    }

    protected function groupUpdate(Request $request)
    {
    	$loggedUser = Auth::user();
    	$group = $this->repository->find($request->input('id'));
    	if($loggedUser->getGod() && $group)
    	{
            $validator = $this->validator($request->all(), $group->getName());
            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                    );
                }
    		$newUsers=$request->input('updatedUsers', []);
    		$groupUsers = $group->getUsers();
    		foreach($group->getUsers() as $user)
    		{
    			if(in_array($user->getId(),$newUsers)){
    				unset($newUsers[array_search($user->getId(), $newUsers)]);
    			} else {
    				$user->removeGroup($group);
    			}
    		}
    		foreach($newUsers as $userId)
    		{
    			$user= $this->em->find("Behigorri\Entities\User", $userId);
    			$user->addGroup($group);
    		}
            $group->setName($this->repository->avoidSqlInjection($request->input('name')));
            $group->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
    		$this->em->persist($group);
    		$this->em->flush();
			return redirect('/admin/group');
    	}

    }

    protected function groupView($id)
    {
    	$loggedUser = Auth::user();
    	$group = $this->repository->find($id);
    	if($loggedUser->getGod() && $group)
    	{
    		$groupUsers = $group->getUsers();
    		$allUsers = $this->em->getRepository('Behigorri\Entities\User')->findAll();
    		$filteredUsers=[];
    		foreach($groupUsers as $user)
    		{
    			$filteredUsers[$user->getId()] = [
    					'name' => $user->getName(),
    			];

    		}
    		return view('god.groupView')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'group' => $group,
    				'users' => $filteredUsers
    		]);
    	}
    	return redirect('/');

    }

    protected function groupSearch(Request $request)
    {
    	$loggedUser = Auth::user();
    	if($loggedUser->getGod())
    	{
    		$search['name'] = $request->input('search');
    		$result = $this->repository->search($search);
    		$groups = $this->repository->paginate($result);
    		return view('god.groups')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'datas' => $groups
    		]);
    	}else{
    		return redirect('/');
    	}

    }

}
