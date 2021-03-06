@extends('layout')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/chunkedUploader.min.css') }}" />

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Create Excercise</h1>
			<a class="garra-button-a" href="{{ url('excercises') }}">Manage Excercises</a>
		</header>

		@include('partials/errors')

		<div>
			<form class="garra-form text-right" method="POST" enctype="multipart/form-data">
				{!! csrf_field() !!}

				<div class="form-inputs-content garra-box">

					<div class="form-text">

						<div>
							<input id="excercise_title" type="text" name="title" value="{{ old('title') }}" placeholder="Title"/>
						</div>

						<div class="names">
							<select name="thematic_id" class="garra-form-select">
								<option value="" disabled selected>Select a thematic</option>
								@foreach ($thematics as $thematic)
									<option value="{{ $thematic->id }}">{{ $thematic->title }}</option>
								@endforeach
							</select>

							<select id="consultant_id" name="consultant_id" class="garra-form-select">
								<option value="" disabled selected>Select a consultant</option>
								@foreach ($consultants as $consultant)
									<option value="{{ $consultant->id }}">{{ $consultant->first_name }} {{ $consultant->last_name }}</option>
								@endforeach
							</select>
						</div>

						<div>
							<textarea rows="10" id="excercise_content" name="content" placeholder="Description">{{ old('content') }}</textarea>
						</div>
					</div>

					<div class="avatar">
						<div id="excerciseMedia" class="avatar-image" style="background: url('{{ URL::asset('images/default-avatar.png') }}');"></div>

						<input id="excercise_media" type="hidden" name="media_id" value=""/>
						
						<a href="#" id="changeMedia" class="btn-action" style="padding: 10px 20px;">Add/Change Media</a>
					</div>
				</div>

				<input type="submit" value="Create Excercise" style="float: left;" />
			</form>
		</div>
	</div>

	<script type="text/javascript">
		(function() {
			document.getElementById('changeMedia').addEventListener('click', function(e) {
				e.preventDefault();

				modal("{{ url('medias/all') }}", function() {
					var tasks = document.querySelectorAll('[data-task]');
					var submit = document.getElementById('select-media');

					submit.addEventListener('click', function(e) {
						e.preventDefault();

						var media = document.querySelector('input[type="radio"]:checked');
						var label = document.querySelector('label[for="' + media.getAttribute('id') + '"]');
						var url = label.dataset.url;

						var id = media.getAttribute('value');
						var type = label.dataset.type;

						document.getElementById('excerciseMedia').innerHTML = getMediaPreview(url, type, 'controls');
						document.getElementById('excercise_media').setAttribute('value', id);

						closeModal();
					});

					for (var i = 0; i < tasks.length; i++) {
						if (tasks[i].dataset.task) {
							uploaders[tasks[i].dataset.id] = new ChunkedUploader(tasks[i]);           
						}
					}

					if ('onLine' in navigator) {
						window.addEventListener('online', onConnectionFound);
						window.addEventListener('offline', onConnectionLost);
					}

					var inputs = document.querySelectorAll('input[name="avatar"]');
					var i = 0;

					for (; i < inputs.length; i++) {
						inputs[i].addEventListener('change', function(e) {
							selectImage(e.target);

							submit.classList.remove('hide');
						});
					}
				});
			});
		})();

		function beforeUpload(e, file) {
			var response = JSON.parse(e.target.response);

			var formData = new FormData();
			formData.append("_token", document.querySelector('input[name="_token"]').value);
			formData.append("type", response.type);
			formData.append("file_name", response.path);

			var xhttp = new XMLHttpRequest();

			xhttp.onreadystatechange = function(e) {
				if (xhttp.readyState == 4) {
					var response = JSON.parse(e.target.response);

					var li = document.createElement('li');

					var label = document.createElement('label');
					label.setAttribute('for', 'input-radio-' + response.id);
					label.setAttribute('data-url', response.url);
					label.setAttribute('data-type', response.type);

					var input = document.createElement('input');
					input.setAttribute('id', 'input-radio-' + response.id);
					input.setAttribute('type', 'radio');
					input.setAttribute('name', 'avatar');
					input.setAttribute('value', response.id);

					label.innerHTML = getMediaPreview(response.url, response.type);
					label.appendChild(input);
					label.addEventListener('change', function(e) {
						selectImage(e.target);

						document.getElementById('select-media').classList.remove('hide');
					});

					li.appendChild(label);

					document.getElementById('img-list').appendChild(li);
				}
			};

			xhttp.open("POST", "{{ url('medias/images') }}", true);
			xhttp.send(formData);
		}

		function selectImage(element) {
			var label = document.querySelector('label.selected');

			if (label != null) {
				label.classList.remove('selected');
			}

			document.querySelector('label[for="' + element.id + '"]').classList.add('selected');
		}
	</script>
@endsection

<script src="{{ URL::asset('js/chunkedUploader.js') }}"></script>
<style type="text/css">
	.chunk-uploader-submit {
		margin: 15px 0 !important;
	}

	.mediaGrid {
		margin: 0 auto;
		float: initial;
		display: block;
	}
</style>