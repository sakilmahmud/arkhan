<?php
// Start the session
session_start();

include("../connection.php");
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, destroy the session to log them out
    session_destroy();
    // Redirect the user to a login page or any other page
    header('Location: login.php');
    exit;
} else {
    // If the user is not logged in, you can redirect them to a login page or another appropriate page
    header('Location: login.php');
    exit;
}
?>