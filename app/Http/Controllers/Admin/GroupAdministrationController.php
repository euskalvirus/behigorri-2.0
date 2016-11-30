<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Behigorri\Entities\Group;
use Auth;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

class GroupAdministrationController extends Controller
{
    protected $repository;
    protected $em;
    
    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    
    public function groupAdministration()
    {
        $loggedUser = Auth::user();
        //$groups = $this->em->getRepository('Behigorri\Entities\Group');
        $groups = $users = DB::table('Group')->paginate(15);
        //$data = $groups->findAll();
        //var_dump($data);exit;
        if($loggedUser->getGod())
        {
            return view('god.groups')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => $groups 
            ]);
        } else {
        	//return redirect()->back();
            return redirect('/');
        }
    }
    
    public function newGroup()
    {
    	$loggedUser = Auth::user();
    	$users = $this->em->getRepository('Behigorri\Entities\User');
    	$data = $users->findAll();
    	if($loggedUser->getGod())
    	{
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
    	$group = new Group();
    	$group->setName($request->input('name'));
    	$users=$request->input('users');
    	//var_dump($users);exit;
    	if($users == null){
    		$users = [];
    	}
    	foreach ($users as $userId)
    	{
    		$user= $this->em->find("Behigorri\Entities\user", $userId);
    		$user->addGroup($group);
    		//var_dump($user);exit;
    		//$group->addUser($user);
    	}
    	
    	///FALTA ENCRIPTAR Y GUARDAR LOS DATOS EN EL SERVIDOR
    	$this->em->persist($group);
    	$this->em->flush();
    	
    	
    	return redirect('/admin/group');
    	//return redirect()->back();
    	
    }
    
    protected function groupDelete($id)
    {
    	$loggedUser = Auth::user();
    	//$group = $this->repository->find($id);
    	$group = $this->em->find("Behigorri\Entities\Group",$id);
    	if($loggedUser->getGod())
    	{
    		$users = $group->getUsers();
    		foreach ($users as $user)
    		{
    			$group->removeUser($user);
    		}
    		$this->em->remove($group);
    		$this->em->flush();
    	}
    	//return redirect()->back();
    	return redirect('/admin/group');
    	//Redirect::back();
    	 
    }
    
    protected function groupEdit($id)
    {
    	$loggedUser = Auth::user();
    	//$group = $this->repository->find($id);
    	
    	if($loggedUser->getGod())
    	{
    		$group = $this->em->find("Behigorri\Entities\Group",$id);
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
    	//$group = $this->repository->find($id);
    	 
    	if($loggedUser->getGod())
    	{
    		$newUsers=$request->input('updatedUsers', []);
    		$group = $this->em->find("Behigorri\Entities\Group",$request->input('id'));
    		$groupUsers = $group->getUsers();
    		
    		//var_dump($newUsers);exit;
    		
    		foreach($group->getUsers() as $user)
    		{
    			if(in_array($user->getId(),$newUsers)){
    				unset($newUsers[array_search($user->getId(), $newUsers)]);
    			} else {
    				$user->removeGroup($group);
    				//$group->removeUser($group);
    			}
    		}
    		//var_dump($newUsers);exit;
    		
    		foreach($newUsers as $userId)
    		{
    			$user= $this->em->find("Behigorri\Entities\User", $userId);
    			$user->addGroup($group);
    			//$group->addUser($user);
    		}
    		
    		$this->em->persist($group);
    		$this->em->flush();
    		//return redirect()->back();
			return redirect('/admin/group');	
    	}
    	
    }
    
    protected function groupView($id)
    {
    	$loggedUser = Auth::user();
    	//$group = $this->repository->find($id);
    	 
    	if($loggedUser->getGod())
    	{
    		$group = $this->em->find("Behigorri\Entities\Group",$id);
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
    
}