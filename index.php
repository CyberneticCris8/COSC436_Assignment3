<?php
session_start();
require_once 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['userPassword'] ?? '';

    // Signup
    if (isset($_POST['displayName'])) {
        $displayName = $_POST['displayName'];

        // Check if username already exists
        $check_sql = "SELECT username FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user 
            $insert_sql = "INSERT INTO users (username, password, screenName) VALUES (?, ?, ?)";
            $hash_pwd = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $username, $hash_pwd, $displayName);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['screenName'] = $displayName;
                // $_SESSION['success_message'] = "Successfully logged in!";
                header("Location: chatroom.php");
                exit;
            } else {
                // echo '<p id="alert-message">' . "Error logging in!" . '</p>';
                $error = "Signup Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
    // Login
    else {
        $login_sql = "SELECT username, password, screenName FROM users WHERE username = ?";
        $stmt = $conn->prepare($login_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['screenName'] = $user['screenName'];
                header("Location: chatroom.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
        $stmt->close();
    }
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
    <?php
    // Overlay for sucess/failure when login/siging Up
    if (isset($error)): ?>
        <div id="error-overlay"
            style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 10000; justify-content: center; align-items: center;">
            <div
                style="background-color: #f44336; color: white; padding: 40px; border-radius: 10px; max-width: 500px; position: relative; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); text-align: center;">
                <button type="button"
                    style="position: absolute; top: 10px; right: 15px; font-size: 40px; font-weight: bold; color: white; cursor: pointer; border: none; background: none; line-height: 1; padding: 0; width: 40px; height: 40px;"
                    onclick="document.getElementById('error-overlay').remove();">&times;</button>
                <h2 style="margin-bottom: 20px;">Error</h2>
                <p style="font-size: 18px; margin: 0; padding-right: 30px;"><?php echo htmlspecialchars($error); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Chat Room via PHP Web Sockets</th>
        </tr>
        <tr>
            <td>By: Cristopher Castro, Alexander Boyd, & Andrew Bodnar</td>
            <td><button id="helpBtn" class="helpBtn">Help</button></td>
            <td><button id="signupBtn" class="signupBtn">Signup</button></td>
            <td><button id="loginBtn" class="loginBtn">Login</button></td>
        </tr>
    </table>
    <div id="help-overlay" class="help-overlay">
        <div class="overlay-help">
            <button id="help-closeBtn" class="help-closeBtn">&times;</button>
            <h1>Welcome To Our Chatroom!</h1>
            <p>
                Click 'Signup' to sign up for a new account! Each account has a username, password, and a screenname.
                The screenname is the
                name that will be displayed to other users, while your username and password are used to log in to your
                account. To log in, click the
                'Login' button! You will then be prompted to enter your username and password. Each username must be
                unique.
            </p>
            <p>
                Once logged in, you can add a new chatroom by clicking the plus '+' icon to the right of the 'Available
                Rooms' header, or join another user's
                chatroom listed in the 'Available Rooms' box. When creating a chatroom, leave the key field blank to
                create an unlocked room. Otherwise, enter
                a key to create a locked chatroom. For the chatroom name, make sure the name you enter is not already
                taken. Each chatroom has a unique name,
                and a duplicate name will result in denial when creating the room.
            </p>
            <p>
                When joining, rooms with the 'Locked' status require a key in order to join. You cannot join a locked
                chatroom without this key. To see which
                chatroom you are currently in, view the 'Room Name' header at the top of the message log box. If joining
                a locked room, you will see a 'Joined Successfully'
                message below the submit button if the key is correct. If joining an unlocked room, you will instantly
                join and the 'Room name' header will update.
            </p>
            <p>
                You can always log out with the 'Logout' button at the top-right, or view this help overlay again by
                clicking the 'Help' button at anytime!
            </p>
            <p>
                Thank you for using our chatroom!
            </p>
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
            <div id="login-overlay-message"></div>
        </div>
    </div>
</body>

</html>