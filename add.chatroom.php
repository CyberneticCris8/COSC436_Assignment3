<?php
// gets server information for connection
$servername = "localhost";
$username = "436_mysql_user";
$password = "123pwd456";
$dbname = "436db";

// creates connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "failure", "message" => $conn->connect_error]);
    exit;
}

// selects all chatroom information (name & key)
$sql = "SELECT chatroomName, roomKey FROM list_of_chatrooms";
$result = $conn->query($sql);

// appends each row from the result to an array of chatrooms
$chatrooms = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $chatrooms[] = $row;
    }
    echo json_encode(["status" => "success", "chatrooms" => $chatrooms]);
} else {
    echo json_encode(["status" => "failure", "message" => $conn->error]);
}

// closes connection
$conn->close();
?>