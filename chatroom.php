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
        const chatDisplay = document.getElementById("chat-overlay");

        const helpBtn = document.getElementById("helpBtn");
        const closeHelpBtn = document.getElementById("help-closeBtn");

        const logoutBtn = document.getElementById("logoutBtn")
        const closeLogout = document.getElementById("logout-closeBtn");

        const chatBtn = document.getElementById("add-rooms");
        const closeChat = document.getElementById("chat-closeBtn")


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
<div class="chatrooms-container">
    <table class="chatroom-tb">
        <thead class="chatroom-thead">
            <tr>
                <th class="section-hds">
                    <h1 class="available-rooms">Available Rooms</h1>
                    <button id="add-rooms" class="add-rooms">+</button>
                    <div id="chat-overlay" id="chat-overlay">
                        <div id="overlay-chat" class="overlay-chat">
                            <form action="" method="POST">
                                <button id="chat-closeBtn" class="chat-closeBtn">&times;</button>
                                <label for="chatName">Chatroom Name:</label><br>
                                <input type="text" id="chatName" name="chatName"><br>
                                <label for="chatKey">Chatroom Key:</label><br>
                                <input type="text" id="chatKey" name="chatKey"><br>
                            </form>
                        </div>
                    </div>
                </th>
            </tr>
        </thead>
            <tr class="room-headers">
                <th>
                    <p class="room-name">Room Name</p>
                </th>
                <th>
                    <p class="room-status">Status</p>
                </th>
                <th>
                    <p class="join-room">Join?</p>
                </th>
            </tr>
            <tbody>

            </tbody>
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
        </tbody>
    </table>
</div>
<button onclick="window.location.href='index.php'">Go back to index</button>
</body>
</html> 