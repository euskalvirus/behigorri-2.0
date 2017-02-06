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
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Support\Collection as Collection;

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
        $groups = $this->paginate($this->repository->findAll(),15);
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
    	  $users=$request->input('users');
    		if($users == null){
    		    $users = [];
    	     }
    	  foreach ($users as $userId)
    	  {
    		    $user= $this->em->find("Behigorri\Entities\user", $userId);
    		    $user->addGroup($group);
    	  }
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
        if($groupName!=='' && $data['name'] !== $groupName)
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
    		$this->em->persist($group);
    		$this->em->flush();
			return redirect('/admin/group');
    	}

    }

    protected function groupView($id)
    {
    	$loggedUser = Auth::user();
    	$group = $this->repository->find($id);
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
    		$groups = $this->paginate($result,15);
    		return view('god.groups')->with([
    				'user' => $loggedUser,
    				'title' => 'BEHIGORRI PASSWORD MANAGER',
    				'datas' => $groups
    		]);
    	}else{
    		return redirect('/');
    	}

    }
    private function paginate($items,$perPage)
    {
    	$pageStart = \Request::get('page', 1);
    	// Start displaying items from this number;
    	$offSet = ($pageStart * $perPage) - $perPage;

    	// Get only the items you need using array_slice
    	$itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

    	return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

}
