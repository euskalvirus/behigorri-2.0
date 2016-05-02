<?php
namespace App\Http\Controllers;

use Behigorri\Entities\User;
use Auth;
use SebastianBergmann\Environment\Console;
use Behigorri\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;

class IndexController extends Controller
{
    protected $repository;
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    public function index()
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