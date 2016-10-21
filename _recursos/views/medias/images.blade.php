

<div class="form-inputs-content" style="overflow: scroll; height: 100%;">
	<div class="input-container width-75">
		<ul id="img-list" class="modal-images garra-box">
			<li>
				<label for="input-radio-0" data-url="{{ url('images/default-avatar.png') }}" data-type="image">
					<div class="media-thumb" style="background:url('{{ url('images/default-avatar.png') }}');"></div>
					<input id="input-radio-0" type="radio" name="avatar" value="0" />
				</label>
			</li>

			@foreach ($medias as $image)
				<li>
					<label for="input-radio-{{ $image->id }}" data-url="{{ $image->full_url() }}" data-type="{{ $image->type }}">
						<div class="media-thumb" style="background:url('{{ url($image->url) }}');"></div>
						<input id="input-radio-{{ $image->id }}" type="radio" name="avatar" value="{{ $image->id }}" />
					</label>
				</li>
			@endforeach
		</ul>	
	</div>

	<div class="modal-drag add-files">
		<div data-task="chunk-uploader" data-id="1" data-url="{{ url('medias/upload') }}" data-csrf='{!! csrf_field() !!}' data-accept="image/*"></div>

		<a href="#" class="btn-link hide" id="select-media">Use selected media</a>
	</div>
</div>
