<?php
session_start();
include('config/dbcon.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['status'] = "Please log in to disable your account.";
    header('Location: login.php');
    exit(0);
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Update the user status in the database to 'disabled'
$disable_query = "UPDATE users SET designation=0 WHERE UserId='$user_id'";
$disable_query_run = mysqli_query($con, $disable_query);

if ($disable_query_run) {
    // Log the user out after disabling the account
    session_destroy();
    $_SESSION['status'] = "Your account has been disabled successfully.";
    header('Location: login.php');
    exit(0);
} else {
    $_SESSION['status'] = "Something went wrong. Please try again.";
    header('Location: user_dashboard.php');
    exit(0);
}
?>
