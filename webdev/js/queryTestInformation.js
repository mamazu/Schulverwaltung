var selectBox;

function loadStudents(adress, callback_func) {
	var httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function (callback) {
		if ((httpRequest.readyState === 4 || httpRequest.readyState === 0) && httpRequest.status === 200) {
			callback_func(httpRequest.responseText);
		}
	};
	httpRequest.open('GET', adress);
	httpRequest.send();
}

function putInSelect(content) {
	// Defining select box clearing
	function clearBox(box) {
		for (var i = box.options.length - 1; i >= 0; i--)
			box.remove(i)
	}

	// Parsing json
	var data = JSON.parse(content);
	if (data.error != null) {
		alert('Could not retreive information (' + data.error + ')');
		return;
	}
	clearBox(selectBox);
	if (data.studentList.length == 0) {
		alert("This class has no students assigned to it");
	}
	// Creating a new option list
	data.studentList.forEach(function (student) {
		var element = document.createElement('option');
		element.appendChild(document.createTextNode(student));
		selectBox.appendChild(element);
	});
}

function queryStudents(classId) {
	loadStudents('\\Schulverwaltung/api/StudentList.php?classId=' + classId, putInSelect);
	selectBox = document.getElementsByName("studentList")[0];
}