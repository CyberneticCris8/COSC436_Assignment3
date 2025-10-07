<?php 

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
        const helpDisplay = document.getElementById("help-overlay");
        const logoutDisplay = document.getElementById("logout-overlay");

        const helpBtn = document.getElementById("helpBtn");
        const closeHelpBtn = document.getElementById("help-closeBtn");

        const logoutBtn = document.getElementById("logoutBtn")
        const closeLogout = document.getElementById("logout-closeBtn");

        function showOverlay(overlayId) {
        overlayId.style.display = 'flex';
        }
        function hideOverlay(overlayId) {
        overlayId.style.display = 'none';
        }

        helpBtn.addEventListener('click', () => {
            showOverlay(helpDisplay);
        });
        closeHelpBtn.addEventListener('click', () => {
            hideOverlay(helpDisplay);
        });

        logoutBtn.addEventListener('click', () => {
            showOverlay(logoutDisplay);
        });
        closeLogout.addEventListener('click', () => {
            hideOverlay(logoutDisplay);
        });

    });
</script>
<table>
    <tr>
        <th>Chat Room via PHP Web Sockets</th>
</tr>
    <tr>
        <td>By: Cristopher Castro</td>
        <td><button id="helpBtn">Help</button></td>
        <td><button id="logoutBtn">Logout</button></td>
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
        <button id=logout class="logout">Yes</button>
    </div>
</div>
<div class="blank-cell">
</div>
<div class="rooms-header">
    <h1>Available Rooms</h1>
</div>
<div id="available-rooms" class="available-rooms">
    <table class="chatroom-tb">
        <thead class ="chatroom-thead">
            <tr>
                <th id="room-name" class="room-name">Room Name</th>
                <th id="room-status" class="room-status">Status</th>
                <th id="join-room" class="join-room">Join</th>
            </tr>
            <tbody>
            </tbody>
        </thead>
    </table>
</div>
<div>
    <h1 id="current-room">Current Room</h1>
</div>
<div id="chatroom" class="chatroom">
    <table class="chatroom-tb">
        <thead class ="chatroom-thead">
            <tr>
            </tr>
            <tbody>
            </tbody>
        </thead>
    </table>
<div class="blank-chat-cell"></div>
    <label for="msg"></label>
    <input type="text" id="msg" name="msg">
    <button id="send-msg" class="send-msg">Send Message</button>
</div>
<div>
<button onclick="window.location.href='index.php'">Go back to index</button>
</body>
</html> 