<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Page.php';
$HTML = new HTMLGenerator\Page('Chat with your friends', ['form.css', 'chat.css'], ['chat/updateChat.js', 'chat/chatHeight.js'], null, 1);
$HTML->outputHeader();
?>
<!-- Receiving chat messages -->
<h2>Global chat</h2>
<div id="chatBox"><div id="chatContent"></div><aside id="onlineUsers"></aside></div>
<!-- Sending chat messages -->
<form action="sendChatMessage.php" method="post" class="sendMessages">
	<input type="text" placeholder="Enter your message here!" name="message" id="newMessage" autocomplete="off"/>
	<button type="submit">Send</button>
</form>
<?php
$HTML->outputFooter();
?>
