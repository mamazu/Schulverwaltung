var chat, onlineUsers;

function reloadContent(adress, destination) {
    var httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function () {
        if ((httpRequest.readyState === 4 || httpRequest.readyState === 0) && httpRequest.status === 200) {
            return destination.innerHTML = httpRequest.responseText;
        }
    };
    httpRequest.open('GET', adress);
    httpRequest.send();
}

function checkChanges() {
    reloadContent("chatMessages.php", chat);
    reloadContent("onlineUser.php", onlineUsers);
}

function startChat() {
    chat = document.getElementById("chatContent");
    onlineUsers = document.getElementById("onlineUsers");

    if (!chat || !onlineUsers) {
        console.log("Couldnt find elements");
    } else {
        console.log("Updating elements");
        checkChanges();
        setInterval(checkChanges, 2000);
    }
}

window.addEventListener("load", startChat);