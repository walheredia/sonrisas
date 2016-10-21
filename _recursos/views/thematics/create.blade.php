@extends('layout')

@section('content')

<div class="content">
	<header>
		<h1 class="title">Create Thematic</h1>
		<a class="garra-button-a" href="{{ url('thematics') }}">Manage Thematics</a>
	</header>

	@include('partials/errors')

	<div>
		<form class="garra-form text-right" method="POST" enctype="multipart/form-data">
			{!! csrf_field() !!}

			<div class="form-inputs-content garra-box">
				<input id="thematic_title" type="text" name="title" value="{{ old('title') }}" placeholder="Thematic name"/>
			</div>

			<input type="submit" value="Create Thematic" style="float: left;"/>
		</form>
	</div>
</div>
@endsection