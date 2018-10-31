

function checkMail() {
	var subject = document.getElementsByName('subject')[0].value;
	if (subject.length == 0) {
		alert('The subject can not be empty.');
		return false;
	}
	var content = document.getElementsByName('message')[0].value;
	if (content.length == 0) {
		alert('The content can not be empty.');
		return false;
	}
	return true;
}