var container;
function toggleVisibilty() {
	var currentStyle = container.style.display;
	if (container.childNodes.length == 0)
		loadParticipantList('participantsField.php')
	if (currentStyle === 'block')
		container.style.display = 'none';
	else
		container.style.display = 'block';
}

function loadParticipantList(adress) {
	var httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function () {
		if ((httpRequest.readyState === 4 || httpRequest.readyState === 0) && httpRequest.status === 200) {
			container.innerHTML = httpRequest.responseText;
		}
	};
	httpRequest.open('GET', adress);
	httpRequest.send();
}

window.addEventListener('load', function () {
	document.getElementsByName('private')[0].addEventListener("change", toggleVisibilty);
	container = document.getElementById('participantsField')
}, false);
