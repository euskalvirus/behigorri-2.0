<?php
namespace App\Http\Controllers;

use Behigorri\Entities\User;
use Auth;

class IndexController extends Controller
{

    public function index()
    {
        $loggedUser = Auth::user();
        var_dump($loggedUser->getGod());
    }
}