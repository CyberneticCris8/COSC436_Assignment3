<?php 
//  php -S 0.0.0.0:8080 
// Get username, password, & display name 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['userPassword']; 
    $displayName = $_POST['displayName']; 
    
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

        // Get sign overlay, open/close buttons
        const signupDisplay = document.getElementById("signup-overlay");
        const signupBtn = document.getElementById("signupBtn");
        const closeSignupBtn = document.getElementById("signup-closeBtn");
        const signup = document.getElementById("signup");
        signupBtn.addEventListener('click', () => {
            showOverlay(signupDisplay);
        });
        closeSignupBtn.addEventListener('click', () => {
            hideOverlay(signupDisplay);
        });

        // Get login overlay, open/close buttons
        const loginDisplay = document.getElementById("login-overlay");
        const loginBtn = document.getElementById("loginBtn");
        const closeLogin = document.getElementById("login-closeBtn")
        const login = document.getElementById("login");
        loginBtn.addEventListener('click', () => {
            showOverlay(loginDisplay);
        });
        closeLogin.addEventListener('click', () => {
            hideOverlay(loginDisplay);
        });
});
</script>
<table>
    <tr>
        <th>Chat Room via PHP Web Sockets</th>
</tr>
    <tr>
        <td>By: Cristopher Castro, Alexsander Boyd, & Andrew Bodnar</td>
        <td><button id="helpBtn" class="helpBtn">Help</button></td>
        <td><button id="signupBtn" class="signupBtn">Signup</button></td>
        <td><button id="loginBtn" class="loginBtn">Login</button></td>
    </tr>
</table>
<div id="help-overlay" class="help-overlay">
    <div class="overlay-help">
        <button id="help-closeBtn" class="help-closeBtn">&times;</button>
        <h1>Welcome To Our Chatroom!</h1>
        <p>Test</p>
    </div>
</div>
<div id="signup-overlay" class="signup-overlay">
    <div id="overlay-signup" class="overlay-signup">
        <form action="" method="POST">
            <button id="signup-closeBtn" class="signup-closeBtn">&times;</button>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="userPassword">Password:</label><br>
                <input type="password" id="userPassword" name="userPassword" required><br>
                <label for="displayName">Display Name:</label><br>
                <input type="text" id="displayName" name="displayName" required><br>
                <input type="submit" id="signup" value="Submit">
        </form>
    </div>
</div>
<div id="login-overlay" class="login-overlay">
    <div id="overlay-login" class="overlay-login">
        <form action="" method="POST">
            <button id="login-closeBtn" class="login-closeBtn">&times;</button>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="userPassword">Password:</label><br>
            <input type="password" id="userPassword" name="userPassword" required><br>
            <input type="submit" id="login" value="Submit">
        </form>
    </div>
</div>
<button onclick="window.location.href='chatroom.php'">Go to chatroom.php</button>
</body>
</html>