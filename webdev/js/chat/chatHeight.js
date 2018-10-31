function addChatHeight() {
	var chatBox = document.getElementById('chatBox');
	var chatHeight = window.innerHeight - 300;
	chatBox.style.height = chatHeight + 'px';
}

window.onload = function () { addChatHeight(); };
