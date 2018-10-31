function markInvalidFields(invalid) {
	//Marking all invalid fields red
	for (var i = 0; i < invalid.length; i++) {
		invalid[i].style.backgroundColor = 'red';
		invalid[i].style.borderColor = 'black';
	}
}

function checkMail() {
	var valid = true;
	var invalid = [];

	//Subject can not be empty
	var subjectTag = document.getElementsByName('subject')[0];
	if (subjectTag.value.length === 0) {
		valid = false;
		invalid.push(subjectTag);
		alert('Subject can\'t be empty.');
	}

	//Message content can not be the default message
	var messageTag = document.getElementsByName('message')[0];
	if (messageTag.value.length < 5) {
		valid = false;
		invalid.push(messageTag);
		alert('The text has to be at least 5 characters long.');
	}

	markInvalidFields(invalid);
	return valid;
}

function allReceive(executer) {
	document.getElementsByName('receiver[]')[0].disabled = (executer.checked) ? 'disabled' : '';
}