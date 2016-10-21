<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Consultants;
use App\User;

class ConsultantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consultants = Consultants::paginate();

        return view('consultants/index', compact('consultants'));
    }

    public function create() {
        return view('consultants/create');
    }

    public function created() {
        $this->validate(request(), [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:consultants'],
        ]);

        $data = request()->all();
        $data['media_id'] = ($data['media_id'] != '0' && $data['media_id'] != '') ? $data['media_id'] : null;

        $consultant = Consultants::create($data);

        return redirect()->to('consultants/update/'.$consultant->id);
    }

    public function update($id) {
        $consultant = Consultants::findOrFail($id);

        return view('consultants/update', compact('consultant'));
    }

    public function updated($id) {
        $this->validate(request(), [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $data = request()->all();

        $consultant = Consultants::findOrFail($id);
        $consultant->fill($data);
        $consultant['media_id'] = ($consultant['media_id'] != '0') ? $consultant['media_id'] : null;
        $consultant->save();

        return redirect()->to('consultants/update/'.$consultant->id);
    }

    public function delete($id) {
        $consultant = Consultants::findOrFail($id);
        $consultant->delete();

        return redirect()->to('consultants');
    }

    public function profile() {
        return view('profile');
    }

    public function users() {
        $users = User::paginate();

        return view('auth/user', compact('users'));
    }

    public function createUsers() {
        return view('auth/addUser');
    }

    public function createdUser() {
        $this->validate(request(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $data = request()->all();
        $data['password'] = bcrypt($data['password']);
        User::create($data);

        return redirect()->to('users');
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->to('users');
    }
}
