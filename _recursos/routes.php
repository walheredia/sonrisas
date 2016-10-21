<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['middleware' => 'auth', 'uses' => 'ExcercisesController@home']);
Route::post('/', ['middleware' => 'auth', 'uses' => 'ExcercisesController@updateHome']);

Route::get('/consultants', ['middleware' => 'auth', 'uses' => 'ConsultantsController@index']);
Route::get('/consultants/add', ['middleware' => 'auth', 'uses' => 'ConsultantsController@create']);
Route::post('/consultants/add', ['middleware' => 'auth', 'uses' => 'ConsultantsController@created']);
Route::get('/consultants/update/{id}', ['middleware' => 'auth', 'uses' => 'ConsultantsController@update'])->where('id', '[0-9]+');
Route::post('/consultants/update/{id}', ['middleware' => 'auth', 'uses' => 'ConsultantsController@updated'])->where('id', '[0-9]+');
Route::post('/consultants/delete/{id}', ['middleware' => 'auth', 'uses' => 'ConsultantsController@delete'])->where('id', '[0-9]+');

Route::get('/profile', ['middleware' => 'auth', 'uses' => 'ConsultantsController@profile']);

Route::get('/thematics', ['middleware' => 'auth', 'uses' => 'ThematicsController@index']);
Route::get('/thematics/add', ['middleware' => 'auth', 'uses' => 'ThematicsController@create']);
Route::post('/thematics/add', ['middleware' => 'auth', 'uses' => 'ThematicsController@created']);
Route::get('/thematics/update/{id}', ['middleware' => 'auth', 'uses' => 'ThematicsController@update'])->where('id', '[0-9]+');
Route::post('/thematics/update/{id}', ['middleware' => 'auth', 'uses' => 'ThematicsController@updated'])->where('id', '[0-9]+');
Route::post('/thematics/delete/{id}', ['middleware' => 'auth', 'uses' => 'ThematicsController@delete'])->where('id', '[0-9]+');

Route::get('/excercises', ['middleware' => 'auth', 'uses' => 'ExcercisesController@index']);
Route::get('/excercises/update/{id}', ['middleware' => 'auth', 'uses' => 'ExcercisesController@update'])->where('id', '[0-9]+');
Route::post('/excercises/update/{id}', ['middleware' => 'auth', 'uses' => 'ExcercisesController@updated'])->where('id', '[0-9]+');
Route::get('/excercises/add', ['middleware' => 'auth', 'uses' => 'ExcercisesController@create']);
Route::post('/excercises/add', ['middleware' => 'auth', 'uses' => 'ExcercisesController@created']);
Route::post('/excercises/delete/{id}', ['middleware' => 'auth', 'uses' => 'ExcercisesController@delete'])->where('id', '[0-9]+');

Route::get('/medias', ['middleware' => 'auth', 'uses' => 'MediasController@index']);
Route::get('/medias/add', ['middleware' => 'auth', 'uses' => 'MediasController@add']);
Route::put('/medias/upload', ['middleware' => 'auth', 'uses' => 'MediasController@upload']);
Route::post('/medias/upload', ['middleware' => 'auth', 'uses' => 'MediasController@uploaded']);
Route::post('/medias/delete/{id}', ['middleware' => 'auth', 'uses' => 'MediasController@delete'])->where('id', '[0-9]+');
Route::get('/medias/images', ['middleware' => 'auth', 'uses' => 'MediasController@images']);
Route::post('/medias/images', ['middleware' => 'auth', 'uses' => 'MediasController@uploadedImage']);
Route::get('/medias/all', ['middleware' => 'auth', 'uses' => 'MediasController@all']);

Route::auth();
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', ['middleware' => 'auth', 'uses' => 'Auth\AuthController@getLogout']);

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/users', ['middleware' => 'auth', 'uses' => 'ConsultantsController@users']);
Route::get('/users/add', ['middleware' => 'auth', 'uses' => 'ConsultantsController@createUsers']);
Route::post('/users/add', ['middleware' => 'auth', 'uses' => 'ConsultantsController@createdUser']);
Route::post('/users/delete/{id}', ['middleware' => 'auth', 'uses' => 'ConsultantsController@deleteUser'])->where('id', '[0-9]+');
Route::get('auth/register', ['middleware' => 'auth', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('auth/register', ['middleware' => 'auth', 'uses' => 'Auth\AuthController@postRegister']);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::get('delete/error', ['middleware' => 'auth', 'uses' => function() {
	return 'Ok!';
}]);

Route::post('api/login', 'ApiController@login');
Route::post('api/signup', 'ApiController@signup');
Route::get('api/thematics', 'ApiController@thematics');
Route::post('api/comment', 'ApiController@comment');
Route::get('api/consultant', 'ApiController@consultant');
Route::get('api/home', 'ApiController@home');
Route::post('api/view', 'ApiController@addView');
Route::post('api/update', 'ApiController@updateUser');
Route::get('api/popular', 'ApiController@popular');

Route::get('app/users', 'AppController@users');
Route::get('app/comments', 'AppController@commnets');
Route::post('thematic/order', [
	'middleware' => 'auth', 
	'uses' => 'ThematicsController@order'
]);
