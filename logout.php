<?php
session_start(); // Start the session (if not already started)

// Destroy the session
session_destroy();

// Redirect to the login page or any other page
header("Location: index.html"); // Replace "login.php" with your login page URL
exit(); 
?>
