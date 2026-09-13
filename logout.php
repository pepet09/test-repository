<?php
// Start the session to gain access to it
session_start();

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session container entirely
session_destroy();

// Redirect back to the login landing screen
header("Location: /proposed_dpwh_repository/login.php");
exit();
?>
