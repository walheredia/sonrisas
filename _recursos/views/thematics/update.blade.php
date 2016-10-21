@extends('layout')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/chunkedUploader.min.css') }}" />

@section('content')

	<div class="content">
		<header>
			<h1 class="title">Update Thematic</h1>

			<ul class="garra-top-list">
				<li><a class="garra-button-a" href="{{ url('thematics') }}">Manage Thematics</a></li>
				<li>
					<form style="margin: 0;" id="delete-media-form-{{ $thematic->id }}" method="POST" action="{{ url('thematics/delete', [$thematic->id]) }}">
						{!! csrf_field() !!}

						<input class="delete-btn" data-id="{{ $thematic->id }}" type="submit" value="Delete Thematic" />
					</form>
				</li>
				<li><a class="garra-button-a" href="{{ url('thematics/add') }}">Add Thematic</a></li>
			</ul>
		</header>

		@include('partials/errors')

		<div>
			<form class="garra-form text-right" method="POST" enctype="multipart/form-data">
				{!! csrf_field() !!}

				<div class="form-inputs-content garra-box">
					<input id="thematic_title" type="text" name="title" value="{{ $thematic->title }}" />
				</div>

				<input type="submit" value="Update Thematic" style="float: left;"/>
			</form>
		</div>
	</div>

	<script>
		(function() {
			var buttons = document.getElementsByClassName('delete-btn');

			var i = 0;
			for (; i < buttons.length; i++) {
				buttons[i].addEventListener('click', function(event) {
					event.preventDefault();
					var id = event.target.dataset.id;

					alert(
						'Are you sure you want to delete the thematic?',
						false, 
						'Delete thematic',
						{
							Accept: function() {
								document.getElementById('delete-media-form-' + id).submit();
								closeFancyAlert();
							},
							Cancel: function() {
								closeFancyAlert();
							},
						}
					);
				});
			}
		})();
	</script>
@endsection