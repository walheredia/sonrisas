<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\AppUsers;
use App\Comments;

class AppController extends Controller
{
    public function users() {
    	$users = AppUsers::paginate();

        return view('app/users', compact('users'));
    }

    public function commnets() {
    	$comments = Comments::paginate();

        return view('app/comments', compact('comments'));
    }
}
