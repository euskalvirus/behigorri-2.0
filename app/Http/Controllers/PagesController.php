<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class PagesController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('index')->with([
            'user' => $user,
            'title' => 'Vengo del controller'
        ]);
    }

    /*public function patan($id)
    {
        $nombre = "alain";
        $cancion = Song::find($id);
        return view('pages.patan')->with([
            'nombre' => 'alain',
            'num' => $id,
            'cancion' => $cancion['title']
        ]);
    }*/
    public function login(Request $request)
    {
        $credentials = [
            'name' => $request->input('user'),
            'password' => $request->input('password')
        ];
        if (Auth::check()) {
            dd("Ya estas dentro melon");
        }
        if (Auth::attempt($credentials)) {
            var_dump(Auth::user());
            var_dump("Todo mal");exit;
            // Authentication passed...
//             return redirect()->intended('dashboard');
        } else {
            var_dump($credentials);
            var_dump(Auth::attempt($credentials));exit;
        }
        $usuario = User::find($request->input('user'),$request->input('password'));
        //return view('pages.welcome', compact('lista'));
        return view('pages.welcome');
    }
    
    public function update(Request $request, $id)
    {
        echo ($request);
        echo ($id);
        return 'naino';
    }
}
