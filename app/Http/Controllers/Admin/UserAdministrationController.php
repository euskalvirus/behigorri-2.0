<?php
namespace App\Http\Controllers\admin;

use Behigorri\Entities\User;
use Auth;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Validator;
use Illuminate\Http\Request;
use Mail;


class UserAdministrationController extends Controller
{
    protected $repository;
    
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    
    public function userAdministration()
    {
        $loggedUser = Auth::user();
        $users = $this->repository->findBy(['god' => false]);
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
        /*$pass = '123456';
        //$encryptPass="";
        var_dump($request);exit;
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
                );
        }
        
        Auth::login($this->create($request->all()));
        
        return redirect(route('adminUser'));*/
        $this->sendEmailReminder($request, 0);
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
        $loggedUser = Auth::user();
    
        Mail::send('activation', ['user' => $loggedUser], function ($m) use ($loggedUser) {
            $m->from($loggedUser->getEmail(), 'Activation Email');
    
            $m->to('azabaleta@barnetik.com', 'alain')->subject('Activation Email!');
        });
    }
    
    public function editUser($id)
    {
        $loggedUser = Auth::user();
        $users = $this->repository->find($id);
        if($loggedUser->getGod())
        {
            return view('god.userEdit')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => $users
            ]);
        } else {
            return redirect('/');
        }
    }
    
}