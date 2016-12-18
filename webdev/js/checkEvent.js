/**
 * Created by mamazu on 9/17/2016.
 */
function checkEvent() {
	var valid = true;
	var invalid = [];

	//Name of the event can't be empty
	if (document.getElementsByName('eventName')[0].value.length == 0) {
		valid = false;
		invalid.push(document.getElementsByName('name')[0]);
		alert('Name can\'t be empty.');
	}

	var dateRegEx = /(\d{1,2})([.\/])(\d{1,2})\2?(\d{4})?$/g;
	//Start time
	if (document.getElementsByName('start')[0].value.length == 0) {
		var string = document.getElementsByName('start')[0].value;
		var result = dateRegEx.test(string);
		if (!result) {
			valid = false;
			invalid.push(document.getElementsByName('start')[0]);
			alert('Invalid format for starting date');
		}
	}

	//End time
	if (document.getElementsByName('end')[0].value.length == 0) {
		var string = document.getElementsByName('end')[0].value;
		var result = dateRegEx.test(string);
		if (!result) {
			valid = false;
			invalid.push(document.getElementsByName('end')[0]);
			alert('Invalid format for ending date');
		}
	}
	return valid;
}

//OnChange method to see unsaved values
function changed(element) {
	element.style.backgroundColor = 'blue';
	element.title = 'Unsaved changes';
}
