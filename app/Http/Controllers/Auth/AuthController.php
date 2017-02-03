<?php

namespace App\Http\Controllers\Auth;

use Behigorri\Entities\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\Request;
//use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Auth;
Use Session;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';
    protected $em;
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->em = $em;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:User',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(bcrypt($data['password']));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    protected function getLogin()
    {
      return view('auth.login');
    }
    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/auth/login');
    }
}
