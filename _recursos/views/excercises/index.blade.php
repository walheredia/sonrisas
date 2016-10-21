@extends('layout')

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Manage Excercises</h1>
			<a class="garra-button-a" href="{{ url('excercises/add') }}">Add Excercise</a>
		</header>

		@if (count($excercises) > 0)
			<ul class="garra-ul">
				@foreach ($excercises as $excercise)
				<li class="garra-box garra-container-1">
					<div class="consultant-container">
						<div class="data data-names">
							<div class="d-content">{{ $excercise->title }}</div>
						</div>

						<div class="data data-names">
							<div class="d-content">
								<div>
									@if ($excercise->consultant() != null)
										{{ $excercise->thematic()->title }}
									@else
										Empty
									@endif
								</div>
								<div class="cons-name">
									@if ($excercise->consultant_id != null)
										{{ $excercise->consultant()->first_name }} {{ $excercise->consultant()->last_name }}
									@else
										Empty
									@endif
								</div>
							</div>
						</div>
						<div class="data">
							<div class="d-content">{{ substr(strip_tags($excercise->content), 0, 100) }}...</div>
						</div>

						<div class="actions">
							<div class="a-content">
								<form id="delete-media-form-{{ $excercise->id }}" method="POST" action="{{ url('excercises/delete', [$excercise->id]) }}">
									{!! csrf_field() !!}

									<input class="btn-action delete-btn" data-id="{{ $excercise->id }}" type="submit" value="Delete" />
								</form>

								<a class="btn-action" href="{{ url('excercises/update', [$excercise->id]) }}">Edit</a>
							</div>
						</div>

					</div>
				</li>
				@endforeach
			</ul>
			{!! $excercises->render() !!}
		@else
			<p>Still it has not created any excercise</p>
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
						'Are you sure you want to delete the excercise?',
						false, 
						'Delete excercise',
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