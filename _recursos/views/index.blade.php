@extends('layout')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/chunkedUploader.min.css') }}" />

@section('content')
	<div class="content">
    	<header>
    		<h1 class="title">Configure Home Page</h1>
    	</header>

    	@include('partials/errors')

        <form class="garra-box garra-container-2" method="POST" enctype="multipart/form-data">
        	{!! csrf_field() !!}

        	<div class="input-container width-50" style="display: none;">
	        	<div class="input-container width-100">
	        		<label for="excercise_title">Title</label>
	        		<input id="excercise_title" type="text" name="title" value="{{ $excercise->title }}" />
	        	</div>

	        	<div class="input-container width-50">
	        		<label>Thematic</label>
	        		<select name="thematic_id">
	        			<option value="" disabled {{ ($excercise->thematic_id == null) ? 'selected' : '' }}>Select a thematic</option>
		        		@foreach ($thematics as $thematic)
		        			<option value="{{ $thematic->id }}" {{ ($thematic->id == $excercise->thematic_id) ? 'selected' : '' }}>{{ $thematic->title }}</option>
		        		@endforeach
	        		</select>
	        	</div>

	        	<div class="input-container width-50">
	        		<label>Consultant</label>
	        		<select  name="consultant_id">
	        			<option value="" disabled {{ ($excercise->consultant_id == null) ? 'selected' : '' }}>Select a consultant</option>
		        		@foreach ($consultants as $consultant)
		        			<option value="{{ $consultant->id }}" {{ ($consultant->id == $excercise->consultant_id) ? 'selected' : '' }}>{{ $consultant->first_name }} {{ $consultant->last_name }}</option>
		        		@endforeach
	        		</select>
	        	</div>

	        	<div class="input-container width-100">
	        		<label for="excercise_content">Content</label>
	        		<textarea id="excercise_content" name="content">{{ $excercise->content }}</textarea>
	        	</div>
        	</div>

			<div id="excerciseMedia" class="home-video">
        		@if ($excercise->media_id != null)
					{!! $excercise->media()->getMediaPreview() !!}
				@else
					<img class="mediaGrid" src="{{ URL::asset('images/default-avatar.png') }}" />
				@endif
			</div>	        		

    		<input id="excercise_media" type="hidden" name="media_id" value="{{ $excercise->media_id }}" />

    		<div style="margin: 15px 0px;">
    			<a href="#" id="changeMedia" class="garra-button-a">Add/Change Media</a>
			</div>

    		<div>
    			<input type="submit" value="Update Home Page" />
			</div>

    	</form>

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
		    			url = url.replace(new RegExp('/', 'g'), '\/');
		    			var id = media.getAttribute('value');
		    			var type = label.dataset.type;

		    			document.getElementById('excerciseMedia').innerHTML = getMediaPreview(url, type);
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

    	function getMediaPreview(url, type) {
    		var response = '';

    		if (type == 'image') {
				response = '<img class="mediaGrid" src="{!! url("' + url + '") !!}" />';
			} else if (type == 'video') {
				response = '<video class="mediaGrid" controls>';
				response += '<source src="{!! url("' + url + '") !!}" />';
				response += '</video>';
			} else if (type == 'audio') {
				response = '<audio class="mediaGrid" controls>';
				response += '<source src="{!! url("' + url + '") !!}" />';
				response += '</audio>';
			}

			return response;
    	}

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