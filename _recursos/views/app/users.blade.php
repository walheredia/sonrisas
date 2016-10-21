@extends('layout')

@section('content')
	<div class="content">
		<header>
    		<h1 class="title">App Users</h1>
    	</header>
	</div>

	@if (count($users) > 0)
		<ul class="garra-ul">
			@foreach ($users as $user)
			<li class="garra-box garra-container-2">
				<div class="consultant-container">
	    			<div class="data data-names">
	    				<div class="d-content">
	    					{{ $user->first_name }} {{ $user->last_name }}
	    				</div>
	    			</div>

	    			<div class="data data-mail">
	    				<div class="d-content">
	    					{{ $user->email }}
	    				</div>
	    			</div>
	    		</div>
			</li>
			@endforeach
		</ul>
		{!! $users->render() !!}
	@else
		<p>Still it has not created any user</p>
	@endif

@endsection