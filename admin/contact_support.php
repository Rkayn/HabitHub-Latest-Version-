<?php
session_start();
include('config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Insert data into database
    $query = "INSERT INTO support_messages (name, email, message) VALUES ('$name', '$email', '$message')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['status'] = "Message sent successfully";
        header('Location: confirmationpage.php');
    } else {
        $_SESSION['status'] = "Message sending failed";
        header('Location: contact_support.php');
    }
} else {
    $_SESSION['status'] = "Invalid request";
    header('Location: contact_support.php');
}
?>
