<?php 
//  php -S 0.0.0.0:8080
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
        const helpDisplay = document.getElementById("help-overlay");
        const signupDisplay = document.getElementById("signup-overlay");
        const loginDisplay = document.getElementById("login-overlay");

        const helpBtn = document.getElementById("helpBtn");
        const signupBtn = document.getElementById("signupBtn");
        const loginBtn = document.getElementById("loginBtn");

        const closeHelpBtn = document.getElementById("help-closeBtn");
        const closeSignupBtn = document.getElementById("signup-closeBtn");
        const closeLogin = document.getElementById("login-closeBtn")

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

        signupBtn.addEventListener('click', () => {
            showOverlay(signupDisplay);
        });
        closeSignupBtn.addEventListener('click', () => {
            hideOverlay(signupDisplay);
        });

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
        <td>By: Cristopher Castro</td>
        <td><button id="helpBtn">Help</button></td>
        <td><button id="signupBtn">Signup</button></td>
        <td><button id="loginBtn">Login</button></td>
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
                <input type="text" id="username" name="username"><br>
                <label for="userPassword">Password:</label><br>
                <input type="password" id="userPassword" name="userPassword"><br>
                <label for="displayName">Display Name:</label><br>
                <input type="text" id="displayName" name="displayName"><br>
                <input type="submit" value="Submit">
        </form>
    </div>
</div>
<div id="login-overlay" class="login-overlay">
    <div id="overlay-login" class="overlay-login">
        <form action="" method="POST">
            <button id="login-closeBtn" class="login-closeBtn">&times;</button>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <label for="userPassword">Password:</label><br>
            <input type="password" id="userPassword" name="userPassword"><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>
</body>
</html>