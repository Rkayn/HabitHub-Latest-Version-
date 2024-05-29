<?php
session_start();
include 'config/dbcon.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT Name, Email, ProfilePhoto FROM users WHERE UserId = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profilePhoto);
$stmt->fetch();
$stmt->close();

$response = array(
    'username' => $username,
    'email' => $email,
    'profilePhoto' => $profilePhoto
);

echo json_encode($response);
?>
