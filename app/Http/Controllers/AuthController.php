<?php
class AuthController extends BaseController {
	public function getLogin()
    {
        $user = Auth::user();
        // Verificamos si hay sesión activa
        if (Auth::check())
        {
            // Si tenemos sesión activa mostrará la página de inicio
            return Redirect::to('/')->with('user', $user)->withInput();
        }
        // Si no hay sesión activa mostramos el formulario
        return View::make('login');
    }
}