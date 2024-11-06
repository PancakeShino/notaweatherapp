<?php
session_start();
// Clear session data or destroy the session
$_SESSION = []; // Clear the session data
session_destroy(); // Destroy the session

// Optionally return a response
echo json_encode(["status" => "logged out"]);
?>
