<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
     {
        return view('auth.register');
    }

    public function store(Request $request)
    {
       //modificar el request para no repetir el username

       $request->request->add(['username' => Str::slug( $request->username)]);

       //validacion
       $this->validate($request,[
        'name'=> 'required|min:4|max:10',
        'username'=>'required|min:4|max:20|unique:users',
        'email'=>'required|email|min:4|max:40|unique:users',
        'password'=>'required|confirmed|min:6'
       ]);

       User::create([
        'name'=>$request->name,
        'username'=>$request->username,
        'email'=>$request->email,
        'password'=>Hash::make( $request->password)
       ]);

       //auntenticar usuario
       auth()->attempt($request->only('email', 'password'));

       //redireccionar usuario
       return redirect()->route('posts.index', auth()->user()->username);
   }
}