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

    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        $this->repository = $em->getRepository('Behigorri\Entities\Group');
    }

    public function groupAdministration()
    {
        $loggedUser = Auth::user();
        //$groups = $this->em->getRepository('Behigorri\Entities\Group');
        //$groups = $users = DB::table('Group')->paginate(15);
        //$groups = $this->paginate($this->repository->findAll(),15);
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
    		//return redirect()->back();
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
    		if($users == null){
    		    $users = [];
    	     }
    	  foreach ($users as $userId)
    	  {
    		    $user= $this->em->find("Behigorri\Entities\user", $userId);
    		    $user->addGroup($group);
    	  }

        $salt = \Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_KEYBYTES);
        $salt2= random_bytes(32);
        $salt3 = hash("sha256", '123123' . $salt);
        //var_dump($salt);exit();
        $keyFactoryFile  = fopen(storage_path() . '/group.key', "w") or die("Unable to open file!");
        fwrite($keyFactoryFile  , $salt);
        fclose($keyFactoryFile );
        $encryptionKey = KeyFactory::deriveEncryptionKey('123123', $salt2);
        $group->setDecryptPassword(bcrypt('123123'));
        $group->setSalt($salt);
    	  $this->em->persist($group);
    	  $this->em->flush();


    	  return redirect('/admin/group');
     }else{
      return redirect('/');
     }
    	//return redirect()->back();

    }
    private function validator(array $data, $groupName)
    {
        if(!empty($groupName) && $data['name'] !== $groupName)
        {
    	       return Validator::make($data, [
    			            'name' => 'required|max:255|unique:Group'
    	              ], $this->repository->getValitationMessages());
        } else{
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
        foreach ($datas as $data)
    		{
    			$data->setGroup(null);
    		}
        $this->em->persist($group);
    		$this->em->remove($group);
    		$this->em->flush();
    	}
    	return redirect('/admin/group');

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
      //$group = $this->em->find("Behigorri\Entities\Group",$request->input('id'));
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
      dd($group->getPublicKey());
      //$group = $this->em->find("Behigorri\Entities\Group",$id);
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
