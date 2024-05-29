<?php
session_start();
include('config/dbcon.php');

$response = array('status' => 'error', 'message' => 'Profile update failed');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

    // Handle file upload if any
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $profilePhoto = 'uploads/' . basename($_FILES['profilePhoto']['name']);
        move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $profilePhoto);
    } else {
        $profilePhoto = null; // Handle the case where no file was uploaded
    }

    // Update user details in the database
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET Name='$username', email='$email', password='$hashedPassword' WHERE UserID='$userId'";
    } else {
        $query = "UPDATE users SET Name='$username', email='$email' WHERE UserID='$userId'";
    }

    if (mysqli_query($con, $query)) {
        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully';
        /*
        if ($profilePhoto) {
            $updatePhotoQuery = "UPDATE users SET profilePhoto='$profilePhoto' WHERE UserID='$userId'";
            mysqli_query($con, $updatePhotoQuery);
        }*/
    } else {
        $response['message'] = 'Failed to update profile';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
