@extends('layouts.login')

@section('content')
<div class="login-content">
	<img src="{{ URL::asset('images/bconsicious-logo.png') }}">
	
	<form class="garra-form login-form" role="form" method="POST" action="{{ url('/login') }}">
		{{ csrf_field() }}
		<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
		<input id="password" type="password" class="form-control" name="password" placeholder="Password">

		<div class="remember-me">
			<input type="checkbox" name="remember"><p>Remember Me</p>
		</div>

		<div class="submit-forgot">
			<div class="submit-btn">
				<button type="submit">Login</button>
			</div>

			<div class="forgot-btn">
				<a href="{{ url('password/email') }}">Forgot your password?</a>
			</div>
		</div>

	</form>
</div>
@endsection
