@if (!$errors->isEmpty())
	<div class="errors">
		<p>No se ha podido realizar la operacón:</p>

		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif