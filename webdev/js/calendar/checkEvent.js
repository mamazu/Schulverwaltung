/**
 * Created by mamazu on 9/17/2016.
 */
function checkEvent() {
	var valid = true;
	var invalid = [];

	//Name of the event can't be empty
	var eventNameTag = document.getElementsByName('eventName')[0];
	if (eventNameTag.value.length === 0) {
		valid = false;
		invalid.push(eventNameTag);
		alert('Name can\'t be empty.');
	}

	var dateRegEx = /(\d{1,2})([.\/])(\d{1,2})\2?(\d{4})?$/g;
	//Start time
	var startTag = document.getElementsByName('start')[0];
	if (!dateRegEx.test(startTag.value)) {
		valid = false;
		invalid.push(startTag);
		alert('Invalid format for starting date');
	}

	//End time
	var endTag = document.getElementsByName('end')[0];
	if (!dateRegEx.test(endTag.value)) {
		valid = false;
		invalid.push(endTag);
		alert('Invalid format for ending date');
	}

	return valid;
}

//OnChange method to see unsaved values
function changed(element) {
	element.style.backgroundColor = 'blue';
	element.title = 'Unsaved changes';
}
