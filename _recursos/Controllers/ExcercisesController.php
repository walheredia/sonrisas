<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Excercises;
use App\Thematics;
use App\Consultants;

class ExcercisesController extends Controller
{
    public function index() {
        $excercises = Excercises::where('id', '<>', 1)->paginate();

        return view('excercises/index', compact('excercises'));
    }

    public function update($id) {
        if ($id != 1) {
            $excercise = Excercises::findOrFail($id);
            $thematics = Thematics::all();
            $consultants = Consultants::all();

            return view('excercises/update', compact('excercise', 'thematics', 'consultants'));
        } else {
            return view('errors/404');
        }
    }

    public function updated($id) {
        $this->validate(request(), [
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]);

        $data = request()->all();
        $data['media_id'] = ($data['media_id'] == '' || $data['media_id'] == '' || $data['media_id'] == '0') ? null : $data['media_id'];

        $excercise = Excercises::findOrFail($id);
        $excercise->fill($data);
        $excercise->save();

        return redirect()->to('excercises/update/'.$excercise->id);
    }

    public function create() {
        $thematics = Thematics::all();
        $consultants = Consultants::all();

        return view('excercises/create', compact('thematics', 'consultants'));
    }

    public function created() {
        $this->validate(request(), [
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]);

        $data = request()->all();
        $data['media_id'] = ($data['media_id'] != '0' && $data['media_id'] != '') ? $data['media_id'] : null;

        $excercise = Excercises::create($data);

        return redirect()->to('excercises/update/'.$excercise->id);
    }

    public function  home() {
        $excercise = Excercises::find(1);
        $thematics = Thematics::all();
        $consultants = Consultants::all();

        return view('index', compact('excercise', 'thematics', 'consultants'));
    }

    public function updateHome() {
        $this->validate(request(), [
            'title' => ['required', 'max:255'],
            'content' => ['required'],
        ]);

        $data = request()->all();
        $data['media_id'] = ($data['media_id'] == '' || $data['media_id'] == '' || $data['media_id'] == '0') ? null : $data['media_id'];

        $excercise = Excercises::find(1);
        $excercise->fill($data);
        $excercise->save();

        return redirect()->to('/');
    }

    public function delete($id) {
        if ($id != 1) {
            $excercise = Excercises::findOrFail($id);
            $excercise->delete();

            return redirect()->to('excercises');
        } else {
            return view('errors/404');
        }
    }
}
