@extends('layout')

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Manage Thematics</h1>

			<a class="garra-button-a" href="{{ url('thematics/add') }}">Add Thematic</a>
		</header>

		@if (count($thematics) > 0)
			<ul class="garra-ul">
				@foreach ($thematics as $thematic)

				<li class="garra-box garra-container-2">
					<div class="consultant-container">
						<select name="position" data-id="{{ $thematic->id }}" class="garra-select position-selector">
							@foreach ($thematics as $t)
							<option value="{{ $t->position }}" {{ ($t->id == $thematic->id) ? 'selected' : '' }}>
								{{ $t->position }}
							</option>
							@endforeach
						</select>

						<div class="data data-thematic">
							<div class="name thematic-title d-content" style="text-transform: capitalize;">
								{{ $thematic->position }}. {{ $thematic->title }}	
							</div>
						</div>

						<div class="actions thematic-action">
							<div class="a-content">
								<form id="delete-media-form-{{ $thematic->id }}" method="POST" action="{{ url('thematics/delete', [$thematic->id]) }}">
									{!! csrf_field() !!}

									<input class="btn-action delete-btn" data-id="{{ $thematic->id }}" type="submit" value="Delete" />
								</form>

								<a class="btn-action" class="btn-link" href="{{ url('thematics/update', [$thematic->id]) }}">Edit</a>
							</div>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
		@else
			<p>Still it has not created any thematic</p>
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

			var selects = document.getElementsByClassName('position-selector');

			for (var i = 0; i < selects.length; i++) {
				selects[i].addEventListener('change', function(e) {
					var id = e.currentTarget.dataset.id;
					var value = e.currentTarget.value;
					
					getOldSelect(id, value, function(old) {
						var oldId = old.dataset.id;

						var form = document.createElement('form');
						form.setAttribute('method', 'POST');
						form.setAttribute('action', '{{ url("/thematic/order") }}');

						var input = document.createElement('input');
						input.setAttribute('name', '_token');
						input.setAttribute('value', '{{ csrf_token() }}');
						input.setAttribute('type', 'hidden');
						form.appendChild(input);

						var input = document.createElement('input');
						input.setAttribute('name', 'new');
						input.setAttribute('value', id);
						input.setAttribute('type', 'text');
						form.appendChild(input);

						var input = document.createElement('input');
						input.setAttribute('name', 'old');
						input.setAttribute('value', oldId);
						input.setAttribute('type', 'text');
						form.appendChild(input);

						form.submit();               
					});
				});
			}

			function getOldSelect(id, value, callback) {
				for (var i = 0; i < selects.length; i++) {
					if (selects[i].value == value && id != selects[i].dataset.id) {
						callback(selects[i]);

						return true;
					}
				}
			}
		})();
	</script>
@endsection