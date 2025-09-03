<?php
// Start the session (required before destroying)
session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// (Optional) Redirect to login page or homepage
header("Location: ../index.php");
exit();
?>
