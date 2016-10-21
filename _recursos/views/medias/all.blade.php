<div class="form-inputs-content" style="overflow: scroll; height: 100%;">
	<div class="input-container width-75">
		<ul id="img-list" class="modal-images garra-box">
			@foreach ($medias as $media)
				<li>
					<label for="input-radio-{{ $media->id }}" data-url="{{ $media->full_url() }}" data-type="{{ $media->type }}">
						{!! $media->getMediaThumb(false) !!}
						<input id="input-radio-{{ $media->id }}" type="radio" name="avatar" value="{{ $media->id }}" />
					</label>
				</li>
			@endforeach
		</ul>	
	</div>

	<div class="modal-drag add-files">
		<div data-task="chunk-uploader" data-id="1" data-url="{{ url('medias/upload') }}" data-csrf='{!! csrf_field() !!}'></div>

		<a href="#" class="btn-link hide" id="select-media">Use selected media</a>
	</div>
</div>