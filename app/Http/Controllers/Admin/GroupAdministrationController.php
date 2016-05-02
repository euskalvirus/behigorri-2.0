<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Behigorri\Entities\Group;
use Auth;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;

class GroupAdministrationController extends Controller
{
    protected $repository;
    
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    
    public function groupAdministration()
    {
        $loggedUser = Auth::user();
        if($loggedUser->getGod())
        {
            return view('god.index')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => ''
            ]);
        } else {
            return view('user.index')->with([
                'user' => $loggedUser,
                'title' => 'WELLCOME SIMPLE USER',
                'datas' => $loggedUser->getUniqueSensitiveData()
            ]);
        }
    }
    
}