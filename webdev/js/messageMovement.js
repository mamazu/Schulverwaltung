
var element;

function show() {
	element = document.getElementsByClassName('userInfoBox');
	setTimeout(hide, 1000);
}

function hide() {
	for(var i=0; i < element.length; i++)
	if (element[i]) {
		element[i].style.display = "none";
	} else {
		window.console.log('Could not find object to hide');
	}
}