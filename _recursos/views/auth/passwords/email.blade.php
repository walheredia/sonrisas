@extends('layouts.login')

<!-- Main Content -->
@section('content')

<div class="login-content">
	<img src="{{ URL::asset('images/bconsicious-logo.png') }}">

	<form class="garra-form login-form" role="form" method="POST" action="{{ url('/password/email') }}">
		{{ csrf_field() }}

		<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email adress">

		<div class="submit-forgot">
			<div class="submit-btn">
				<button type="submit" class="btn btn-primary">Send Password Reset Link</button>
			</div>
		</div>
	</form>
</div>

@endsection
