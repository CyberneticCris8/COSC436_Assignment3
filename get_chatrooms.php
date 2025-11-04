<?php
header('Content-Type: application/json');
session_start();

// Include database connection
include 'dbConnect.php';

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    echo json_encode(["status" => "failure", "message" => "Not authenticated"]);
    exit;
}

// Select all chatroom information
$sql = "SELECT chatroomName, roomKey FROM list_of_chatrooms";
$result = $conn->query($sql);

$chatrooms = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $chatrooms[] = $row;
    }
    echo json_encode(["status" => "success", "chatrooms" => $chatrooms]);
} else {
    echo json_encode(["status" => "failure", "message" => $conn->error]);
}

$conn->close();
?>