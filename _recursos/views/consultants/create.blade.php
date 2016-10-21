@extends('layout')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/chunkedUploader.min.css') }}" />

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Create Consultant</h1>

			<a class="garra-button-a" href="{{ url('consultants') }}">Manage Consultants</a>
		</header>

		@include('partials/errors')

		<div>
			<form class="garra-form text-right" method="POST" enctype="multipart/form-data">
				{!! csrf_field() !!}

				<div class="form-inputs-content garra-box">

					<div class="form-text">
						<div class="names">
							<input id="consultant_first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name"/>
							<input id="consultant_last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name"/>
						</div>

						<div>
							<input id="consultant_email" type="text" name="email" value="{{ old('email') }}" placeholder="Email"/>
						</div>

						<div>
							<textarea rows="10" id="consultant_description" name="description" placeholder="Biography">{{ old('description') }}</textarea>
						</div>
					</div>

					<div class="avatar">

						<div id="consultantAvatar" class="avatar-image" style="background: url('{{ URL::asset('images/default-avatar.png') }}');">
						</div>

						<!--<img id="consultantAvatar" class="avatar-image" src="" />-->

						<input id="consultant_avatar" type="hidden" name="media_id" value="{{ old('media_id') }}" />

						<a href="#" id="changeAvatar" class="btn-action" style="padding: 10px 20px;">Add/Change Profile Image</a>
					</div>
				</div>

				<input type="submit" value="Create Consultant" style="float: left;" />
			</form>
		</div>
	</div>

	<script>
		(function() {
			document.getElementById('changeAvatar').addEventListener('click', function(e) {
				e.preventDefault();

				modal("{{ url('medias/images') }}", function() {
					var tasks = document.querySelectorAll('[data-task]');
					var submit = document.getElementById('select-media');

					submit.addEventListener('click', function(e) {
						e.preventDefault();

						var media = document.querySelector('input[type="radio"]:checked');
						var label = document.querySelector('label[for="' + media.getAttribute('id') + '"]');
						var url = label.dataset.url;

						console.log(url);

						var id = media.getAttribute('value');
						var type = label.dataset.type;

						/*var media = document.querySelector('input[type="radio"]:checked');
						var url = document.querySelector('label[for="' + media.getAttribute('id') + '"] img').getAttribute('src');
						var id = media.getAttribute('value');*/

						document.getElementById('consultantAvatar').innerHTML = getMediaPreview(url, type, 'controls');
						document.getElementById('consultant_avatar').setAttribute('value', id);

						/*document.getElementById('consultantAvatar').setAttribute('style', 'background: url(' + url + ')');
						document.getElementById('consultant_avatar').setAttribute('value', id);*/

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
					label.setAttribute('data-url', response.url);
					label.setAttribute('data-type', response.type);
					
					label.setAttribute('for', 'input-radio-' + response.id);
					var img = document.createElement('img');
					img.setAttribute('src', response.url);
					var input = document.createElement('input');
					input.setAttribute('id', 'input-radio-' + response.id);
					input.setAttribute('type', 'radio');
					input.setAttribute('name', 'avatar');
					input.setAttribute('value', response.id);

					label.appendChild(img);
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
</style>