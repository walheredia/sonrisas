@extends('layout')

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Manage Consultants</h1>

			<a class="garra-button-a" href="{{ url('consultants/add') }}">Add Consultants</a>
		</header>

		@if (count($consultants) > 0)
			<ul class="garra-ul">
				@foreach ($consultants as $consultant)

				<li class="garra-box garra-container-2">

					<div class="consultant-container">
						<div class="consult-box" style="background:url('{{ ($consultant->media_id != null) ?  URL::asset($consultant->media()) : URL::asset('images/default-avatar.png') }}');">
						</div>

						<div class="data" style="min-height:70px;">
							<div class="d-content">
								<div class="name" style="text-transform: capitalize;">{{ $consultant->first_name }} {{ $consultant->last_name }}</div>
								<div class="email">{{ $consultant->email }}</div>
							</div>
						</div>

						<div class="actions" style="min-height:70px;">
							<div class="a-content">
								<form id="delete-media-form-{{ $consultant->id }}" method="POST" action="{{ url('consultants/delete', [$consultant->id]) }}">
									{!! csrf_field() !!}

									<input class="btn-action delete-btn" data-id="{{ $consultant->id }}" type="submit" value="Delete" />
								</form>

								<a class="btn-action" href="{{ url('consultants/update', [$consultant->id]) }}">Edit</a>
							</div>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
		@else
			<p>Still it has not created any consultant</p>
		@endif
	</div>

	<script type="text/javascript">
		(function() {
			var buttons = document.getElementsByClassName('delete-btn');

			var i = 0;
			for (; i < buttons.length; i++) {
				buttons[i].addEventListener('click', function(event) {
					event.preventDefault();
					var id = event.target.dataset.id;

					alert(
						'Are you sure you want to delete this consultant?',
						false, 
						'Delete consultant',
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