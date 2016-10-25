<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class UsuariosController extends Controller
{
	public function getAltaUsuario(){
		return view('alta');
	}
}