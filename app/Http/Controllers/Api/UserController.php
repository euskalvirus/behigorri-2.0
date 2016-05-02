<?php
namespace App\Http\Controllers\Api;

use Auth;
use Doctrine\ORM\EntityManager;


class UserController extends \App\Http\Controllers\Controller
{
    protected $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    
    public function index()
    {
        /*$loggedUser = Auth::user();
        //var_dump($this->repository->getUniqueSensitiveData());exit;
        $senstividata = $loggedUser->getUniqueSensitiveData();
        $repository = EntityManager::getRepository(User::class);
        $repository->getUniqueSensitiveData();*/
        $users = $this->repository->findAll();
        
        //USAR CON FRACTAL PARA USAR CON JS
        return response()->json($users[0]->jsonSerialize());
    }
}