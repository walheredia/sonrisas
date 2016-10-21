@extends('layout')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/chunkedUploader.min.css') }}" />

@section('content')
	<div class="content">
		<header>
			<h1 class="title">Add Medias</h1>

			<a class="garra-button-a" href="{{ url('medias') }}">Manage Medias</a>
		</header>
	</div>

	<div data-task="chunk-uploader" data-id="1" data-url="{{ url('medias/upload') }}" data-csrf='{!! csrf_field() !!}'></div>
	
	<canvas id="canvas-preview"></canvas>
	
	<script>
		(function() {
			var tasks = document.querySelectorAll('[data-task]');

			for (var i = 0; i < tasks.length; i++) {
				if (tasks[i].dataset.task) {
					uploaders[tasks[i].dataset.id] = new ChunkedUploader(tasks[i]);           
				}
			}

			if ('onLine' in navigator) {
				window.addEventListener('online', onConnectionFound);
				window.addEventListener('offline', onConnectionLost);
			}
		})();
	</script>
@endsection

<script>
	
function beforeUpload(e, file) {
	var response = JSON.parse(e.target.response);

	console.log(response);

	var formData = new FormData();
	formData.append("_token", document.querySelector('input[name="_token"]').value);
	formData.append("type", response.type);
	formData.append("file_name", response.path);

	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "{{ url('medias/upload') }}", true);
	xhttp.send(formData);
}

</script>
<script src="{{ URL::asset('js/chunkedUploader.js') }}"></script>