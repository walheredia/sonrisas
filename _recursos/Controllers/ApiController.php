<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\AppUsers;
use App\Excercises;
use App\Comments;
use App\Thematics;
use App\Medias;
use App\Consultants;

use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
	public function login() {
		$validation = $this->validate(request(), [
			'email' => ['required', 'email', 'max:255'],
			'password' => ['required', 'max:255'],
		]);

		if ($validation != null && $validation->fails()) {
			$errors = $validation->errors();

			$response = array(
				'success' => false,
				'errors' => $errors->toJson(),
			);
		} else {
			$data = request()->all();

			$user = AppUsers::where('email', '=', $data['email'])->first();

			if ($user != null && Hash::check($data['password'], $user['password'])) {
				$response = array(
					'success' => true,
					'user' => array(
						'id' => $user->id,
						'first_name' => $user->first_name,
						'last_name' => $user->last_name,
						'email' => $user->email,
					),
					'home' => $this->home(),
				);
			} else {
				$response = array(
					'success' => false,
					'errors' => array(
						'Email or password incorrect',
					),
				);
			}
		}

		return json_encode($response);
	}

	public function signup() {
		$validation = $this->validate(request(), [
			'first_name' => ['required', 'max:255'],
			'last_name' => ['required', 'max:255'],
			'email' => ['required', 'email', 'max:255', 'unique:app_users'],
			'password' => ['required', 'max:255'],
		]);

		if ($validation != null && $validation->fails()) {
			$errors = $validation->errors();

			$response = array(
				'success' => false,
				'errors' => $errors->toJson(),
			);
		} else {
			$data = request()->all();
			$data['password'] = Hash::make($data['password']);

			$user_id = AppUsers::create($data)->id;

			$response = array(
				'success' => true,
				'user_id' => $user_id, 
			);
		}

		return json_encode($response);
	}

	public function home() {
		$home = Excercises::find(1);

		$response = array(
			'success' => false,
			'message' => 'Home page not found.',
		);

		if ($home != null) {
			$media = $home->media();

			$response = array(
				'success' => true,
				'content' => array(
					'title' => $home->title,
					'content' => $home->content,
					'media_url' =>  ($media != null) ? url(str_replace ('\/', '', $media->url)) : null,
					'media_thumbnail' => ($media != null) ? url(str_replace ('\/', '', $media->thumbnail)) : null,
					'media_duration' => ($media != null) ? date('H:i:s', $media->duration) : null,
				),
			);

		}

		return $response;
	}

	public function thematics() {
		$thematics = Thematics::where('position', '<>', null)->orderBy('position', 'ASC')->get();

		foreach ($thematics as $thematic) {
			$thematic['excercises'] = $thematic->getExcercises();
		}

		return json_encode(array(
			'success' => true,
			'thematics' => $thematics,
		));
	}

	public function consultant() {
		$consultants = Consultants::all();

		foreach ($consultants as $consultant) {
			$consultant['media_url'] = $consultant->media();
		}

		return json_encode(array(
			'success' => true,
			'consultants' => $consultants,
		));
	}

	public function comment() {
		$validation = $this->validate(request(), [
			'user_id' => ['required'],
			'user_id' => ['required'],
			'message' => ['required', 'min:5'], 
		]);

		if ($validation != null && $validation->fails()) {
			$errors = $validation->errors();

			$response = array(
				'success' => false,
				'errors' => $errors->toJson(),
			);
		} else {
			$data = request()->all();

			$comment = Comments::create($data);

			$response = array(
				'success' => true,
				'excercise' => $comment->excercise, 
				'user' => $comment->user,
			);
		}

		return json_encode($response);
	}

	public function addView() {
		$data = request()->all();

		$response = array('success' => false);

		$video = Medias::find($data['id']);

		if ($video != null && $video->type == 'video') {
			if ($video->views != null) {
				$video->views = $video->views + 1;
			} else {
				$video->views = 1;
			}

			$video->save();

			$response = array(
				'success' => true, 
				'video' => $video
			);
		}

		return json_encode($response);
	}

	public function updateUser() {
		$validation = $this->validate(request(), [
			'id' => ['required'],
			'password' => ['max:255'], 
			'repeat_password' => ['same:password'], 
			'notifications' => ['boolean'],
		]);

		if ($validation != null && $validation->fails()) {
			$errors = $validation->errors();

			$response = array(
				'success' => false,
				'errors' => $errors->toJson(),
			);
		} else {
			$data = request()->all();

			$user = AppUsers::find($data['id']);
			$user->password = (isset($data['password'])) ? Hash::make($data['password']) : $user->password;
			$user->notifications = (isset($data['notifications'])) ? $data['notifications'] : $user->notifications;
			$user->save();

			$response = array(
				'success' => true,
				'user_id' => $user->id, 
			);
		}

		return json_encode($response);
	}

	public function popular() {
		$popularAux = Medias::where('type', 'video')->orderBy('views', 'DESC')->get();
		$popular = array();

		foreach ($popularAux as $m) {
			$excercise = Excercises::where('media_id', $m->id)
							->where('id', '<>', 1)->first();

			if ($excercise != null) {
				$m->url = url(str_replace ('\/', '', $m->url));
				$m->thumbnail = url(str_replace ('\/', '', $m->thumbnail));
				$m->duration = date('H:i:s', $m->duration);

				$popular[] = array(
					'excercise' => $excercise, 
					'consultant' => $excercise->consultant(),
					'media' => $m,
				);

				if (count($popular) == 10) {
					break;
				}
			}
		}

		return json_encode(array(
			'success' => true,
			'videos' => $popular,
		));
	}
}