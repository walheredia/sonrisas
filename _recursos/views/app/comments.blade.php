@extends('layout')

@section('content')

	<div class="content">
		<header>
    		<h1 class="title">App Users</h1>
    	</header>
	</div>

	@if (count($comments) > 0)
		<ul class="garra-ul">
			@foreach ($comments as $comment)
			<li class="garra-box garra-container-2">
				<div class="consultant-container">
	    			<div class="data data-names">
	    				<div class="d-content">
	    					{{ $comment->user['first_name'] }} {{ $comment->user['last_name'] }}
	    				</div>
	    			</div>

					<div class="data data-names">
	    				<div class="d-content">
	    					{{ $comment->excercise['title'] }}
	    				</div>
	    			</div>

	    			<div class="data data-mail">
	    				<div class="d-content">
	    					{{ $comment->message }}
	    				</div>
	    			</div>
	    		</div>
			</li>
			@endforeach
		</ul>
		{!! $comments->render() !!}
	@else
		<p>Still it has not created any comment</p>
	@endif

@endsection










