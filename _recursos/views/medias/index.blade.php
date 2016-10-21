@extends('layout')

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Manage Medias</h1>

			<a class="garra-button-a" href="{{ url('medias/add') }}">Add Medias</a>
		</header>

		@if (count($medias) > 0)
			<ul class="medias-page">
				@foreach ($medias as $media)
				<li class="garra-box">
					<div class="consultant-container">
						<div class="">{!! $media->getMediaThumb() !!}</div>

						<div class="media-content">
							<div class="media-type">{{ $media->type }}</div>

							<div class="actions" style="min-height: 0;">
								<form id="delete-media-form-{{ $media->id }}" method="POST" action="{{ url('medias/delete', [$media->id]) }}">
									{!! csrf_field() !!}

									<input class="btn-action delete-btn" data-id="{{ $media->id }}" type="submit" value="Delete" />
								</form>
							</div>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
			{!! $medias->render() !!}
		@else
			<p>Still it has not uploaded any media</p>
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
						'Delete media',
						false, 
						'Are you sure you want to delete the media?',
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