<?php
session_start();
session_destroy(); // Destroy all session data

// Redirect to the login page or any other desired page
header("Location: main1.php");
exit();
?>