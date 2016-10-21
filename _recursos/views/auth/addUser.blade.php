@extends('layout')

@section('content')
    <div class="content">
        <header>
            <h1 class="title">Create User</h1>

            <ul class="list">
                <li><a href="{{ url('users') }}">Manage Users</a></li>
            </ul>
        </header>

        @include('partials/errors')

        <form class="form-styles" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="form-inputs-content">
                <div class="input-container width-100">
                    <label for="user_name">Name</label>
                    <input id="user_name" type="text" name="name" value="{{ old('name') }}" />
                </div>

                <div class="input-container width-100">
                    <label for="user_email">Email</label>
                    <input id="user_email" type="text" name="email" value="{{ old('email') }}" />
                </div>

                <div class="input-container width-50">
                    <label for="user_pass">Password</label>
                    <input id="user_pass" type="password" name="password" value="{{ old('password') }}" />
                </div>

                <div class="input-container width-50">
                    <label for="excercise_content">Repeat Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation"  value="{{ old('password_confirmation') }}" />
                </div>
            </div>

            <input type="submit" value="Create User" />
        </form>
    </div>
@endsection