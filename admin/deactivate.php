<?php
include('config/dbcon.php');

if (isset($_GET['id'])) {
    $UserID = $_GET['id'];

    // Update the user's designation to inactive (0)
    $query = "UPDATE users SET designation = 0 WHERE UserID = $UserID";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['status'] = "User Deactivated Successfully";
        header('Location: registered.php'); // Redirect back to the users list
    } else {
        $_SESSION['status'] = "User Deactivation Failed";
        header('Location: registered.php');
    }
} else {
    $_SESSION['status'] = "Invalid User ID";
    header('Location: registered.php');
}
?>
