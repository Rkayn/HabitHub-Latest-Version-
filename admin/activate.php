<?php
include('config/dbcon.php');

if (isset($_GET['id'])) {
    $UserID = $_GET['id'];

    // Update the user's designation to active (1)
    $query = "UPDATE users SET designation = 1 WHERE UserID = $UserID";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['status'] = "User Activated Successfully";
        header('Location:  registered.php'); // Redirect back to the users list
    } else {
        $_SESSION['status'] = "User Activation Failed";
        header('Location:  registered.php');
    }
} else {
    $_SESSION['status'] = "Invalid User ID";
    header('Location: registered.php');
}
?>
