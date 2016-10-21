window.easyModalIsActive = false;

window.nope = function() {};

window.modal = function(url, callback) {
	callback = callback || nope();

	if (!window.easyModalIsActive) {
		window.easyModalIsActive = true;

		var bg = document.createElement('div');
		bg.setAttribute('id', 'easy-modal-bg');
		bg.addEventListener('click', closeModal, false);

		var al = document.createElement('div');
		al.classList.add('easy-modal-wrap');
		al.addEventListener('click', function(e) {
			e.stopPropagation();
		});

		var view = document.createElement('div');
		view.setAttribute('id', 'easy-modal-view');
		view.innerHTML = 'Loading';

		var close = document.createElement('a');
		close.classList.add('close-modal');
		close.innerHTML = 'Close';
		close.addEventListener('click', closeModal, false);

		al.appendChild(close);
		al.appendChild(view);
		bg.appendChild(al);
		document.body.appendChild(bg);

		window.setTimeout(function() {
			bg.classList.add('active');

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
		            document.getElementById('easy-modal-view').innerHTML = xhttp.responseText;

		            callback();
		        }
			};
			xhttp.open("GET", url, true);
			xhttp.send(null);
		}, 10);
	}
};

window.closeModal = function(e) {
	var modal = document.getElementById('easy-modal-bg');
	modal.classList.remove('active');

	window.setTimeout(function() {
		window.easyModalIsActive = false;

		modal.parentElement.removeChild(modal);
	}, 200);
};