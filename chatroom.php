<?php 
// Get chatroom name, chatroom key, and user message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chatroomName = $_POST["chatName"];
    $chatKey = $_POST["chatKey"];
    $userMsg = $_POST["user-msg"];

}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./index.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Functions for displaying/hiding overlays
        function showOverlay(overlayId) {
            overlayId.style.display = 'flex';
        }
        function hideOverlay(overlayId) {
            overlayId.style.display = 'none';
        }
        
        // Get help overlay, open/close buttons
        const helpDisplay = document.getElementById("help-overlay");
        const helpBtn = document.getElementById("helpBtn");
        const closeHelpBtn = document.getElementById("help-closeBtn");
        helpBtn.addEventListener('click', () => {
            showOverlay(helpDisplay);
        });
        closeHelpBtn.addEventListener('click', () => {
            hideOverlay(helpDisplay);
        });

        // Get logout overlay, open/close buttons
        const logoutDisplay = document.getElementById("logout-overlay");
        const logoutBtn = document.getElementById("logoutBtn")
        const closeLogout = document.getElementById("logout-closeBtn");
        logoutBtn.addEventListener('click', () => {
            showOverlay(logoutDisplay);
        });
        closeLogout.addEventListener('click', () => {
            hideOverlay(logoutDisplay);
        });

        // Get chat overlay, open/close buttons
        const chatDisplay = document.getElementById("chat-overlay");
        const chatBtn = document.getElementById("add-rooms");
        const closeChat = document.getElementById("chat-closeBtn");      
        chatBtn.addEventListener('click', () => {
            showOverlay(chatDisplay);
        });
        closeChat.addEventListener('click', () => {
            hideOverlay(chatDisplay);
        });
    });
</script>
<table>
    <tr>
        <th class="page-header">Chat Room via PHP Web Sockets</th>
</tr>
    <tr>
        <td>By: Cristopher Castro, Alexsander Boyd, & Andrew Bodnar</td>
        <td><button id="helpBtn" class="helpBtn">Help</button></td>
        <td><button id="logoutBtn" class="logoutBtn">Logout</button></td>
    </tr>
</table>
<div id="help-overlay" class="help-overlay">
    <div class="overlay-help">
        <button id="help-closeBtn" class="help-closeBtn">&times;</button>
        <h1>Welcome To Our Chatroom!</h1>
        <p>Test</p>
    </div>
</div>
<div id="logout-overlay" class="logout-overlay">
    <div id ="overlay-logout" class="overlay-logout">
        <button id="logout-closeBtn" class="logout-closeBtn">&times;</button>
        <h1>Are You Sure You Want To Logout?</h1>
        <button id=logout class="logout" onclick="window.location.href='index.php'">Yes</button>
    </div>
</div>
<div class="blank-cell">
</div>
<div id="chat-overlay" class="chat-overlay">
    <div id="overlay-chat" class="overlay-chat">
        <form action="" method="POST">
            <button id="chat-closeBtn" class="chat-closeBtn">&times;</button>
                <label for="chatName">Chatroom Name:</label><br>
                <input type="text" id="chatName" name="chatName" required><br>
                <label for="chatKey">Chatroom Key:</label><br>
                <input type="text" id="chatKey" name="chatKey" required><br>
                <input type="submit" id="signup" value="Submit">
        </form>
    </div>
</div>
<div class="chatrooms-container">
    <table class="chatroom-tb">
        <thead class="chatroom-thead">
            <tr>
                <th colspan="3">
                    <h1 class="available-rooms">Available Rooms</h1>
                    <button id="add-rooms" class="add-rooms">+</button>
                </th>
            </tr>
        </thead>
            <tr class="room-headers">
                <th>Room Name</th>
                <th>Status</th>
                <th>Join?</th>
            </tr>
            <tbody></tbody>
    </table>
    <table class="chatroom">
        <thead class="chatroom-thead">
            <tr>
                <th id="current-room" class="current-room">Room Name</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                </tr>
            </tr>
        </tbody>
    </table>
</div>
<div class="user-msgBox">
    <form action="" method="POST">
    <input type="text" id="user-msg" class="user-msg" name="user-msg" placeholder="Type New Message Here" required>
    <input type="submit" id="send-msg" class="send-msg" value="Send Message">
</form>
</div>
<button onclick="window.location.href='index.php'">Go back to index.php</button>
</body>
</html> 