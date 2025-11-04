<?php
// USE PM2 TO KEEP PHP SERVER ON

// Starts session
session_start();

// includes connection made from dbConnect.php
include 'dbConnect.php';

// if username is not set, send to index.php
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}
$username = $_SESSION['username'];
$screenName = $_SESSION['screenName'];

// clears user from the table of current_chatroom_occupants and disconnects client from session
if (isset($_POST['logout'])) {

    // clears the row where the user's screenname is (the chatroom they are currently in)
    if (isset($_SESSION['screenName'])) {
        $screenName = $_SESSION['screenName'];
        $stmt = $conn->prepare("DELETE FROM current_chatroom_occupants WHERE screenname = ?");
        $stmt->bind_param("s", $screenName);
        $stmt->execute();
        $stmt->close();
    }

    // closes connection
    $conn->close();

    // disconnects client from session & returns to index.php
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
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
        var socket = false;

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
            const chatDisplay = document.getElementById("chatroom-add-overlay");
            const chatBtn = document.getElementById("add-rooms");
            const closeChat = document.getElementById("addChat-closeBtn");
            const add_message = document.getElementById("addChat-overlay-message");
            chatBtn.addEventListener('click', () => {
                add_message.textContent = '';
                showOverlay(chatDisplay);
            });
            closeChat.addEventListener('click', () => {
                hideOverlay(chatDisplay);
                add_message.textContent = '';
            });

            // -->
            // JOINING CHATROOMS
            // -->

            // Gets join-chatroom overlay, close button
            const join_overlay = document.getElementById('chatroom-join-overlay');
            const join_close_btn = document.getElementById('joinChat-closeBtn');
            const join_form = document.getElementById('join-chatroom-form');
            const join_message = document.getElementById('joinChat-overlay-message');
            join_close_btn.addEventListener("click", () => {
                hideOverlay(join_overlay);
                join_message.textContent = '';
            });

            // joinChatroom() function.
            // Joins a chatroom given a name and a key. If the room is unlocked, joins immediately. Otherwise,
            // displays an error message and allows the user to retry
            function joinChatroom(chatroom_name, chatroom_key) {
                // if the chatroom is locked, proceed with key verification. Otherwise, attempts to join immediately
                if (chatroom_key.trim() !== '') {
                    join_message.textContent = '';
                    showOverlay(join_overlay);

                    // attempts to join chatroom asynchronously 
                    join_form.onsubmit = async (e) => {
                        // prevents page from reloading
                        e.preventDefault();

                        // gets key and stores data in a FormData object for fetching
                        const entered_key = document.getElementById('join-chatKey').value.trim();
                        const data = new FormData();
                        data.append('chatroomName', chatroom_name);
                        data.append('chatKey', entered_key);

                        try {
                            // sends the data (user's inputs) asynchronously
                            const response = await fetch('join_chatroom.php', {
                                method: 'POST',
                                body: data,
                                headers: { 'Accept': 'application/json' }
                            });

                            // parses the json result
                            const result = await response.json();

                            // updates the current joined room if successful and displays success message
                            if (result.status === 'success') {
                                join_message.textContent = 'Joined successfully!';
                                updateCurrentRoom(chatroom_name);
                                join_form.reset();
                            }
                            else {
                                join_message.textContent = "Invalid key.";
                            }
                        } catch (error) {
                            join_message.textContent = "Error joining chatroom.";
                        }
                    };
                }
                else {
                    fetchJoin(chatroom_name, '');
                }
            }

            // fetchJoin function.
            // fetches the request to join the selected chatroom via its name and key to join_chatroom.php
            async function fetchJoin(chatroom_name, chatroom_key) {
                // stores data in FormData object for fetching
                const data = new FormData();
                data.append('chatroomName', chatroom_name);
                data.append('chatKey', chatroom_key);

                try {
                    // sends the data (user's inputs) asynchronously
                    const response = await fetch('join_chatroom.php', {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    // checks for http errors
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    // parses the json result
                    const result = await response.json();

                    // handles response -> if successful, displays a message stating so and adds the new chatroom
                    // to the list (also resets the form). Else, only displays an error message, indicating failure
                    if (result.status === "success") {
                        updateCurrentRoom(chatroom_name);
                    }
                    else {
                        join_message.textContent = "Error joining chatroom.";
                    }

                    // catches any errors, prints them in the console, and displays an error message
                } catch (error) {
                    join_message.textContent = "Error joining chatroom.";
                }
            }

            // updateCurrentRoom function.
            // Updates the current chatroom by changing the text displayed above the messaging block to the chatroom name
            function updateCurrentRoom(room_name) {
                document.getElementById('current-room').textContent = room_name;
                document.getElementById('joined-chatroom-log').innerHTML = '';
                // clears old messages
                document.getElementById('joined-chatroom-log').innerHTML = '';
            }

            // -->
            // ADDING CHATROOMS
            // -->

            // addChatroom() function.
            // Adds a chatroom (in the front-end) given a name and a key. Does this by adding a row to the list of
            // chatrooms with its name, status, and a join button
            function addChatroom(chatroom_name, chatroom_key) {
                // References the chatroom list table
                const chatroom_list = document.getElementById("available-chatroom-list");

                // Sets status -> locked or unlocked (if key is empty, the room is unlocked)
                let status = 'locked';
                if (chatroom_key.trim() === '') {
                    status = 'unlocked';
                }

                // Creates a new row
                const new_row = document.createElement("tr");
                new_row.classList.add("new-chatroom");

                // Creates the name column
                const col_1 = document.createElement("td");
                col_1.textContent = chatroom_name;

                // Creates the status column
                const col_2 = document.createElement("td");
                col_2.textContent = status;

                // Creates the join column
                const col_3 = document.createElement("td");
                const join_btn = document.createElement("button");
                join_btn.textContent = "Join";
                join_btn.addEventListener("click", () => joinChatroom(chatroom_name, chatroom_key));
                col_3.appendChild(join_btn);

                // Adds all columns to the row, and the row to the table
                new_row.appendChild(col_1);
                new_row.appendChild(col_2);
                new_row.appendChild(col_3);
                chatroom_list.appendChild(new_row);
            }

            // -->
            // MESSAGING IN CHATROOMS
            // -->

            // references form, chat log, and the screenname of the logged in user
            const curr_screenname = "<?php echo addslashes(string: $screenName); ?>";
            const message_form = document.getElementById('msg-form');
            const chat_log = document.getElementById('joined-chatroom-log');

            // when sending a message, displays the sender's message on the frontend and broadcasts it to all other users
            // in the same chatroom
            message_form.addEventListener('submit', (e) => { 
                // prevents reloading page
                e.preventDefault();

                const current_room = document.getElementById('current-room').textContent;

                // if the user is not currently in a valid chatroom, displays error message in the chat log
                if (!current_room || current_room === "Room Name") {
                    chat_log.innerHTML = "<tr><td><i>You are not currently in a chatroom.</i></td></tr>"
                    return;
                }

                // gets message from input field
                const user_msg = message_form['user-msg'].value.trim();

                // aborts if message is empty
                if (!user_msg) return;

                // ensures socket is currently in the OPEN state
                if (window.socket && socket.readyState === WebSocket.OPEN) {
                    // stores message data into a JSON packet
                    const message_packet = JSON.stringify({
                        type: "message", 
                        chatroom: current_room,
                        screenName: curr_screenname, 
                        message: user_msg
                    });

                    // sends the packet via the socket
                    socket.send(message_packet);

                    // displays sent message on the frontend for the sender
                    chat_log.innerHTML += `<tr><td><b>Me:</b> ${user_msg}</td></tr>`;
                    chat_log.scrollTop = chat_log.scrollHeight;

                    // clears form
                    message_form.reset();
                } 
                // displays error message if the socket is not in the OPEN state
                else {
                    chat_log.innerHTML = "<tr><td><i>You are not connected to the chat server.</i></td></tr>";
                }
            });

            // references html elements
            const chatroom_add_form = document.getElementById('add-chatroom-form');
            const submission_message = document.getElementById('addChat-overlay-message');

            // adds event listener when submitting the form
            chatroom_add_form.addEventListener('submit', async (e) => {
                // prevents the page from reloading
                e.preventDefault();

                // references the user's inputs for adding a chatroom
                const room_name = chatroom_add_form.chatName.value.trim();
                const room_key = chatroom_add_form.chatKey.value.trim();

                // if no input is entered for the chatroom name, returns & prompts user to re-enter inputs
                if (!room_name) {
                    submission_message.textContent = "Chatroom name is required";
                    return;
                }

                // stores user's inputs as a FormData object to be sent via fetch
                const data = new FormData(chatroom_add_form);

                try {
                    // sends the data (user's inputs) asynchronously
                    const response = await fetch('add_chatroom.php', {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    // checks for http errors
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    // parses the json result
                    const result = await response.json();

                    // handles response -> if successful, displays a message stating so and adds the new chatroom
                    // to the list (also resets the form). Else, only displays an error message, indicating failure
                    if (result.status === "success") {
                        submission_message.textContent = "Chatroom added successfully!";
                        chatroom_add_form.reset();
                        addChatroom(room_name, room_key);

                        // broadcasts the newly created chatroom to the server
                        socket.send(JSON.stringify({
                            type: "new_chatroom",
                            chatroomName: room_name,
                            roomKey: room_key
                        }));
                    }
                    else {
                        submission_message.textContent = "Chatroom already exists.";
                    }

                    // catches any errors, prints them in the console, and displays an error message
                } catch (error) {
                    submission_message.textContent = "Error adding chatroom.";
                }
            });

            // --> 
            // LOADING CHATROOMS
            // -->

            // loadChatrooms() function.
            // Updates the chatroom list by loading all chatrooms currently in the SQL database
            async function loadChatrooms() {
                try {
                    // fetches chatrooms via get_chatrooms.php
                    const response = await fetch('get_chatrooms.php');
                    const result = await response.json();

                    // if successful, adds each chatroom to the frontend 
                    if (result.status === "success") {
                        result.chatrooms.forEach(room => addChatroom(room.chatroomName, room.roomKey));
                    }
                    else {
                        console.error(result.message);
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            // connectWebSocket() function.
            // connects to the websocket server by creating a new socket if one doesn't already exist
            function connectWebSocket() {
                if (socket) {
                    alert("Already connected");
                    return;
                }

                // creates new websocket and sets procedures for each action
                socket = new WebSocket("ws://" + window.location.hostname + ":8080");
                socket.onopen = () => console.log("Connected to WebSocket server");
                socket.onclose = () => console.log("Disconnected from WebSocket server");
                socket.onmessage = (event) => {
                    console.log("WebSocket msg:", event.data);
                    try {
                        // loads chatrooms added via broadcasting on the server
                        const data = JSON.parse(event.data);
                        if (data.type === "new_chatroom") {
                            addChatroom(data.chatroomName, data.roomKey);
                        }
                        if (data.type === "message") {
                            const currentRoom = document.getElementById('current-room').textContent;

                            // Change data.from to data.screenName
                            if (data.chatroom === currentRoom) {

                                // Check if the screenName from the incoming message matches the current user's screenname
                                const displayName = (data.screenName === curr_screenname) ? "Me" : data.screenName;

                                // Use data.screenName for the bolded name, and data.message for the message content
                                chat_log.innerHTML += `<tr><td><b>${displayName}:</b> ${data.message}</td></tr>`;
                                chat_log.scrollTop = chat_log.scrollHeight;
                            }
                        }
                    } catch (e) {
                        console.error("Invalid WebSocket msg:", e);
                    }
                } // <-- Closing brace for socket.onmessage handler
            } // <-- ADD THIS CLOSING BRACE for connectWebSocket() function

            // connects the websocket and loads chatrooms upon loading the page
            connectWebSocket();
            loadChatrooms();
        });
    </script>
    <table>
        <tr>
            <th class="page-header">Chat Room via PHP Web Sockets</th>
        </tr>
        <tr>
            <td>By: Cristopher Castro, Alexander Boyd, & Andrew Bodnar</td>
            <td><button id="helpBtn" class="helpBtn">Help</button></td>
            <td><button id="logoutBtn" class="logoutBtn">Logout</button></td>
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
    <div id="logout-overlay" class="logout-overlay">
        <div id="overlay-logout" class="overlay-logout">
            <button id="logout-closeBtn" class="logout-closeBtn">&times;</button>
            <h1>Are You Sure You Want To Logout?</h1>
            <form method="POST" action="">
                <button id="logout" class="logout" type="submit" name="logout">Yes</button>
            </form>
        </div>
    </div>
    <div class="blank-cell">
    </div>
    <div id="chatroom-add-overlay" class="chat-overlay">
        <div id="chatAdd-overlay-chat" class="overlay-chat">
            <form id="add-chatroom-form">
                <button id="addChat-closeBtn" class="chat-closeBtn">&times;</button>
                <label for="chatName">Chatroom Name:</label><br>
                <input type="text" id="chatName" name="chatName" required><br>
                <label for="chatKey">Chatroom Key:</label><br>
                <input type="text" id="add-chatKey" name="chatKey"><br>
                <input type="submit" id="addRoom" value="Submit">
            </form>
            <div id="addChat-overlay-message"></div>
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
            <tbody id="available-chatroom-list">
                <!-- rows to be populated here -->
            </tbody>
        </table>
        <table class="chatroom">
            <thead class="chatroom-thead">
                <tr>
                    <th id="current-room" class="current-room">Room Name</th>
                </tr>
            </thead>
            <tbody id="joined-chatroom-log">
                <!-- rows to be populated here -->
            </tbody>
        </table>
    </div>

    <div id="chatroom-join-overlay" class="chat-overlay">
        <div id="chatJoin-overlay-chat" class="overlay-chat">
            <form id="join-chatroom-form">
                <button id="joinChat-closeBtn" class="chat-closeBtn">&times;</button>
                <label for="chatKey">Enter Key:</label><br>
                <input type="password" id="join-chatKey" name="chatKey"><br>
                <input type="submit" id="joinRoom" value="Submit">
            </form>
            <div id="joinChat-overlay-message"></div>
        </div>
    </div>
    <div class="user-msgBox">
        <form id="msg-form">
            <input type="text" id="user-msg" class="user-msg" name="user-msg" placeholder="Type New Message Here"
                required>
            <input type="submit" id="send-msg" class="send-msg" value="Send Message">
        </form>
    </div>
</body>

</html>