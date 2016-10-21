function getMediaPreview(url, type, controls) {
	controls = controls || ''

	var response = '';

	if (type == 'image') {
		response = '<img class="mediaGrid" src="' + url + '" />';
	} else if (type == 'video') {
		response = 	'<video class="mediaGrid" ' + controls + '>';
		response += 	'<source src="' + url + '" />';
		response += '</video>';
	} else if (type == 'audio') {
		response = 	'<audio class="mediaGrid" ' + controls + '>';
		response += 	'<source src="' + url + '" />';
		response += '</audio>';
	}

	return response;
}