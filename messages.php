<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = ($_POST["user-msg"]);
    echo "<tr><td>Me: {$message}</td></tr>";
} else {
    echo "Invalid request.";
}
?>