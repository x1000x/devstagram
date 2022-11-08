<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       return view('perfil.index');
    }

    public function store(Request $request)
    {
        $request->request->add(['username'=>Str::slug($request->username)]);
        
        $this->validate($request, [
        'username' => [
            'required',

            Rule::unique('users', 'username')->ignore(auth()->user()),
          
            'min:3',
            'max:20',
            'not_in:editar-perfil'] ,
        ]);

        if ($request->imagen){
            $imagen= $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension();
     
            $imagenServidor = Image::make($imagen);
            $imagenServidor ->fit(1000, 1000);
     
            $imagePath =public_path('perfiles') . '/' .  $nombreImagen;
            $imagenServidor->save($imagePath);
        }
      //guardar cambios

      $usuario =User::find(auth()->user()->id);
      $usuario->username =$request->username;
      $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
      $usuario->save();

      return redirect()->route('posts.index', $usuario->username);
}
}
