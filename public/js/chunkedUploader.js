Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}

Number.prototype.formatBytes = function() {
    var units = ['B', 'KB', 'MB', 'GB', 'TB'],
        bytes = this,
        i;
 
    for (i = 0; bytes >= 1024 && i < 4; i++) {
        bytes /= 1024;
    }
 
    return bytes.toFixed(2) + units[i];
}

var uploaders = new Array();

function ChunkedUploader(element) {
	this.id = element.dataset.id;
	this.element = element;
	this.files = new Array();
	this.file_to_upload = 0;
	this.url = element.dataset.url;
	this.chunk_size = (1024 * 100);
	this.slice_method;
	this.upload_request = new XMLHttpRequest();
    this.upload_request.onload = this.onChunkComplete;
    this.upload_request.uploader = this;
    this.is_paused = false;
    this.total_chunks = 0;
    this.server_path = null;
    this.video_duration = null;

    this.csrf = null;
    if (typeof element.dataset.csrf != 'undefined') {
    	this.csrf = element.dataset.csrf;
    }

    this.accept = ['image', 'audio', 'video'];
    if (typeof element.dataset.accept != 'undefined') {
    	this.accept = element.dataset.accept;

    	this.accept = this.accept.split('/*');
		for (var i = 0; i < this.accept.length; i++) {
			if (this.accept[i] == '') {
				this.accept.splice(i, 1);
			} else {
				this.accept[i] = this.accept[i].replace('|', '');
			}
		}
    }

	this.init();
}

ChunkedUploader.prototype = {
	init: function() {
		var form = this.createElements({
			element: 'form',
			attributes: {
				id: 'chunked-uploader-form-' + this.id,
				class: 'chunked-uploader-form',
				action: this.url,
				method: 'post',
				'data-id': this.id,
			},
		});

		form.innerHTML = this.csrf;

		var input = this.createElements({
			element: 'input',
			attributes: {
				id: 'input-file-name-' + this.id,
				name: 'file_name',
				value: '',
				type: 'hidden',
			},
		});

		form.appendChild(input);

		input = this.createElements({
			element: 'input',
			attributes: {
				id: 'input-file-type-' + this.id,
				name: 'file_type',
				value: '',
				type: 'hidden',
			},
		});

		form.appendChild(input);

		input = this.createElements({
			element: 'input',
			attributes: {
				id: 'input-file-size-' + this.id,
				name: 'file_size',
				value: '',
				type: 'hidden',
			},
		});

		form.appendChild(input);

		input = this.createElements({
			element: 'input',
			attributes: {
				id: 'input-file-path-' + this.id,
				name: 'path',
				value: '',
				type: 'hidden',
			},
		});

		form.appendChild(input);

		var imageContent = this.createElements({
			element: 'div',
			attributes: {
				id: 'chunked-uploader-images-content-' + this.id,
				class: 'chunked-uploader-images-content',
				'data-id': this.id,
			},
		});

		var input = this.createElements({
			element: 'input',
			attributes: {
				id: 'chunked-uploader-input-' + this.id,
				class: 'chunked-uploader-input',
				type: 'file',
				name: 'uploadFiles',
				'data-id': this.id,
				multiple: true,
			},
			event: {
				trigger: 'change',
				action: this.addFiles,
			}
		});

		if (this.accept != null) {
			input.setAttribute('accept', this.accept);
		}

		var link = this.createElements({
			element: 'a',
			attributes: {
				href: '#',
				id: 'chunked-uploader-link-' + this.id,
				class: 'chunked-uploader-link',
				'data-id': this.id,
			},
			event: {
				trigger: 'click',
				action: this.openFilesSelector,
			},
			content: 'Add Files',
		});

		if (window.FileReader) {
			var p = this.createElements({
				element: 'p',
				attributes: {
					'data-id': this.id,
				},
				content: 'Or drop the files to upload here'
			});

			imageContent.addEventListener('dragover', this.cancel);
			imageContent.addEventListener('dragenter', this.cancel);
			imageContent.addEventListener('drop', this.filesDroped);
		}

		filesP = '(' + this.accept[0].charAt(0).toUpperCase() + this.accept[0].slice(1);
		for (var i = 1; i < this.accept.length; i++) {
			filesP += ((this.accept.length - 1) > i) ? ', ' : ' and ';
			filesP += this.accept[i];
		}
		filesP += ' files are accepted)';

		var p2 = this.createElements({
			element: 'p',
			attributes: {
				'data-id': this.id,
			},
			content: filesP,
		});

		var list = this.createElements({
			element: 'div',
			attributes: {
				id: 'chunk-uploader-list-' + this.id,
				class: 'chunk-uploader-list',
				'data-id': this.id,
			},
		});

		var submit = this.createElements({
			element: 'input',
			attributes: {
				id: 'chunk-uploader-submit-' + this.id,
				class: 'chunk-uploader-submit',
				type: 'submit',
				value: 'Upload Files',
				'data-id': this.id,
				disabled: 'true',
			},
			event: {
				trigger: 'click',
				action: this.startUpload,
			}
		});

		form.appendChild(input);
		imageContent.appendChild(link);
		imageContent.appendChild(p);
		imageContent.appendChild(p2);
		form.appendChild(imageContent);
        form.appendChild(list);
        form.appendChild(submit);
		this.element.appendChild(form);
	},

	createElements: function(config) {
		var el = document.createElement(config.element);

		if (typeof config.attributes != 'undefined') {
			for (var i in config.attributes) {
				el.setAttribute(i, config.attributes[i]);
			}
		}

		if (typeof config.event != 'undefined') {
			el.addEventListener(config.event.trigger, config.event.action);
		}

		if (typeof config.content != 'undefined') {
			el.innerHTML = config.content;
		}

		return el;
	},

	openFilesSelector: function(e) {
		e.preventDefault();

		var input = document.getElementById('chunked-uploader-input-' + this.dataset.id);
		input.click();
	},

	addFiles: function(e) {
		var files = e.target.files;
		var submit = document.getElementById('chunk-uploader-submit-' + this.dataset.id);
		var uploader = uploaders[this.dataset.id];
 
		uploader.addFilesToUploader(files);

        submit.disabled = false;
	},

	filesDroped: function(e) {
		e.preventDefault();

		var id = e.target.dataset.id;
		var uploader = uploaders[id];
		var files = e.dataTransfer.files;

		uploader.addFilesToUploader(files);
	},

	addFilesToUploader: function(files) {
		for (var i = 0; i < files.length; i++) {
			var typeAux = files[i].type.split('/');
        	var type = typeAux[0];

        	if (this.accept.indexOf(type) >= 0) {
        		this.files.push(files[i]);
        	} else {
        		alert('An error was ocurred:\n\n\n - Type file not allowed\n\nWhen try to upload "' + files[i].name + '" file');
        		files.splice(i, 1);
        	}
        }

        if (this.files.length > 0) {
        	var submit = document.getElementById('chunk-uploader-submit-' + this.id);
        	submit.disabled = false;
        }

        this.addFilesPreview(files);
	},

	addFilesPreview: function(files) {
		var id = this.id;
		var list = document.getElementById('chunk-uploader-list-' + id);
		var status = document.getElementById('chunk-uploader-status-' + id);

		for (var i = 0; i < files.length; i++) {
			var typeAux = files[i].type.split('/');
            var type = typeAux[0];
            var sourceType = typeAux[1];

            var preview = this.createElements({
            	element: 'div',
            	attributes: {
            		id: 'chunk-uploader-preview-' + id + '-' + files[i].name,
            		class: 'chunked-uploader-preview',
            	},
            });

            var status = this.createElements({
            	element: 'p',
            	attributes: {
            		id: 'chunk-uploader-preview-status-' + id + '-' + files[i].name,
            		class: 'chunked-uploader-preview-status',
            	},
            	content: '0%',
            });

            preview.appendChild(status);
            list.appendChild(preview);

            var reader = new FileReader();
            reader.attachedFile = files[i];

            reader.addEventListener('progress', function(e) {
            	var status = document.getElementById('chunk-uploader-preview-status-' + id + '-' + e.target.attachedFile.name);

            	status.innerHTML = Math.round((e.loaded * 100) / e.total) + '%';
            });

            reader.addEventListener('loadend', function(e) {
            	var status = document.getElementById('chunk-uploader-preview-status-' + id + '-' + e.target.attachedFile.name);
            	var element = document.getElementById('chunk-uploader-preview-' + id + '-' + e.target.attachedFile.name);
                var bin = this.result;
                var file = e.target.attachedFile;
                var uploader = uploaders[id];

                status.remove();

                if (type == 'video') {
                    uploader.addVideoToList(bin, sourceType, element);
                } else if (type == 'audio') {
                	uploader.addAudioToList(bin, sourceType, element);
                } else {
                    uploader.addImgToList(bin, element);
                }
            });

            reader.readAsDataURL(files[i]);
		}
	},

	addVideoToList: function(bin, sourceType, element) {
		var self = this;

		var video = this.createElements({
			element: 'video',
			attributes: {
				id:'video-preview', 
				controls: 'true',
			},
		});

		var source = this.createElements({
			element: 'source', 
			attributes: {
				src: bin,
				type: 'video/' + sourceType,
			},
		});

        video.appendChild(source);
        element.appendChild(video);

		video.onloadedmetadata = function() {
			var sec_num = video.duration;
			var hours   = Math.floor(sec_num / 3600);
			var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
			var seconds = Math.round(sec_num - (hours * 3600) - (minutes * 60));

			if (hours   < 10) {hours   = "0"+hours;}
			if (minutes < 10) {minutes = "0"+minutes;}
			if (seconds < 10) {seconds = "0"+seconds;}

			var video_duration = hours+':'+minutes+':'+seconds;
		};
	},

	addAudioToList: function(bin, sourceType, element) {
		var video = this.createElements({
			element: 'audio',
			attributes: {
				controls: 'true',
			},
		});

		var source = this.createElements({
			element: 'source', 
			attributes: {
				src: bin,
				type: 'audio/' + sourceType,
			},
		});

        video.appendChild(source);
        element.appendChild(video);
	},

	addImgToList: function(bin, element) {
        element.setAttribute('style', 'background-image: url("' + bin + '")');
	},

	startUpload: function(e) {
		e.preventDefault();

		var button = e.target;
		var id = button.dataset.id;
		var uploader = uploaders[id];

		if (button.classList.contains('pause')) {
			uploader.pause();

			button.classList.remove('pause');
			button.classList.add('resume');
			button.setAttribute('value', 'Resume');
		} else if (button.classList.contains('resume')) {
			uploader.resume();

			button.classList.add('pause');
			button.classList.remove('resume');
			button.setAttribute('value', 'Pause');
		} else {
			uploader.file_to_upload = 0;
			uploader.startUploadFile();

			button.classList.add('pause');
			button.classList.remove('resume');
			button.setAttribute('value', 'Pause');
		}
	},

	startUploadFile: function() {
		this.range_start = 0;
		this.range_end = this.chunk_size;
		this.file_size = this.files[this.file_to_upload].size;
		this.current_chunk = 1;
		this.total_chunks = Math.ceil((this.files[this.file_to_upload].size / this.chunk_size));
		this.getSliceMethod();
		this.server_path = null;

		var statusContent = this.createElements({
			element: 'div',
			attributes: {
				id: 'chunk-uploader-preview-status-background-' + this.id + '-' + this.files[this.file_to_upload].name,
				class: 'chunk-uploader-preview-status-background',
			},
		});

		var status = this.createElements({
        	element: 'p',
        	attributes: {
        		id: 'chunk-uploader-preview-status-' + this.id + '-' + this.files[this.file_to_upload].name,
        		class: 'chunked-uploader-preview-status',
        	},
        	content: '0%',
        });

		statusContent.appendChild(status);
        document.getElementById('chunk-uploader-preview-' + this.id + '-' + this.files[this.file_to_upload].name).appendChild(statusContent);

		if (!this.is_paused) {
			this.upload();
		}
	},

	onChunkComplete: function(e) {
		var uploader = this.uploader;
		var response = JSON.parse(e.target.response);

        if (response.success) {
        	uploader.updateUploadStatus();

        	uploader.server_path = response.path;

        	if (uploader.range_end === uploader.file_size) {
	            uploader.onUploadComplete(e);
	            return;
	        }
	 
	        uploader.range_start = uploader.range_end;
	        uploader.range_end = uploader.range_start + uploader.chunk_size;
        } else if (response.type == 'critical') {
        	var msg = response.status + ': \n\n\n';
        	for (var i = 0; i < response.error.length; i++) {
        		msg += ' - ' + response.error[i] + '\n\n';
        	}
        	msg += 'When try to upload "' + uploader.files[uploader.file_to_upload].name + '" file';

        	alert(msg);

        	uploader.onUploadComplete(e);
	        return;
        } else {
        	console.log(response.status);
        }
 
        if (!uploader.is_paused) {
            uploader.upload();
        }
	},

	onUploadComplete: function(e) {
		beforeUpload(e, this.files[this.file_to_upload]);

		var fileToRemove = document.getElementById('chunk-uploader-preview-' + this.id + '-' + this.files[this.file_to_upload].name);
		this.file_to_upload++;

		if (this.files.length > this.file_to_upload) {
			this.startUploadFile();
		} else {
			this.file_to_upload = 0;
			this.files = new Array();

			var button = document.getElementById('chunk-uploader-submit-' + this.id);
			button.classList.remove('resume');
			button.classList.remove('pause');
			button.setAttribute('value', 'Upload Files');
			button.setAttribute('disabled', true);
		}

		window.setTimeout(function() {
			fileToRemove.classList.add('hide');

			window.setTimeout(function() {
				fileToRemove.remove();
			}, 400);
		}, 300);
	},

	upload: function() {
		var self = this;
		var chunk;
		var file = this.files[this.file_to_upload];

		setTimeout(function() {
			if (self.range_end > self.file_size) {
                self.range_end = self.file_size;
            }

            chunk = file[self.slice_method](self.range_start, self.range_end);

            document.getElementById('input-file-name-' + self.id).setAttribute('value', file.name);
            document.getElementById('input-file-type-' + self.id).setAttribute('value', file.type);
            document.getElementById('input-file-size-' + self.id).setAttribute('value', chunk.size);
            document.getElementById('input-file-path-' + self.id).setAttribute('value', self.server_path);

            self.upload_request.open('PUT', self.url + '?file_name=' + file.name + '&file_type=' + file.type + '&file_size=' + chunk.size + '&path=' + self.server_path + '&duration=' + this.video_duration, true);
            self.upload_request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('[name="_token"]').value);
            self.upload_request.overrideMimeType('application/octet-stream');
 
            if (self.range_start !== 0) {
                self.upload_request.setRequestHeader('Content-Range', 'bytes ' + self.range_start + '-' + self.range_end + '/' + self.file_size);
            }

            self.upload_request.send(chunk);
		}, 20);
	},

	updateUploadStatus: function() {
		var file = this.files[this.file_to_upload];
        var status = document.getElementById('chunk-uploader-preview-status-' + this.id + '-' + file.name);

        status.innerHTML = Math.round((this.current_chunk * 100) / this.total_chunks) + '%';

        this.current_chunk++;
	},

	getSliceMethod: function() {
		var file = this.files[this.file_to_upload];

		if ('mozSlice' in file) {
	        this.slice_method = 'mozSlice';
	    }
	    else if ('webkitSlice' in file) {
	        this.slice_method = 'webkitSlice';
	    }
	    else {
	        this.slice_method = 'slice';
	    }
	},

	pause: function() {
        this.is_paused = true;
    },
 
    resume: function() {
        this.is_paused = false;
        this.upload();
    },

	cancel: function(e) {
        e.preventDefault();

        return false;
    },

    onConnectionFound: function() {
    	var button = document.getElementById('chunk-uploader-submit-' + this.id);

    	if (button.classList.contains('pause')) {
    		this.resume();

    		button.setAttribute('value', 'Pause');
    	} else if (button.classList.contains('resume')) {
    		button.setAttribute('value', 'Resume');
    	} else {
    		button.setAttribute('value', 'Upload Files');
    	}

		if (this.files.length > 0) {
			button.disabled = false;
		}
    },

    onConnectionLost: function() {
    	this.pause();

    	var button = document.getElementById('chunk-uploader-submit-' + this.id);
		button.setAttribute('value', 'Connection Lost');
		button.setAttribute('disabled', true);
    },
};

function onConnectionFound() {
	for (var i = 0; i < uploaders.length; i++) {
		uploaders[i + 1].onConnectionFound();
	}
}

function onConnectionLost() {
	for (var i = 0; i < uploaders.length; i++) {
		uploaders[i + 1].onConnectionLost();
	}
}