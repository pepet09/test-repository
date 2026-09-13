<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config/database.php";

// Verify incoming post request parameters
if (isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query database for matching username profile match
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check text string password structure match
        if ($password === $user['password']) {
            
            // CRITICAL: Overwrite session indicators with incoming credentials
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['fullname']  = $user['fullname'];
            $_SESSION['role']      = $user['role']; // Assigns Chief, Materials Engineer, or Lab Tech dynamically

            // Relocate session straight to main overview dashboard
            header("Location: dashboard.php");
            exit();

        } else {
            // Password mismatch error trap handler
            echo "<script>alert('Invalid password entry. Please try again.'); window.location='login.php';</script>";
            exit();
        }
    } else {
        // Username mismatch error trap handler
        echo "<script>alert('Account profile username not registered.'); window.location='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

?>
