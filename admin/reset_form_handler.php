<?php
session_start();
include('config/dbcon.php');

if (isset($_POST['reset_submit'])) {
    $token = mysqli_real_escape_string($con, $_POST['token']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($password === $confirm_password) {
        // Check if token is valid
        $check_token_query = "SELECT * FROM password_resets WHERE token='$token' LIMIT 1";
        $check_token_query_run = mysqli_query($con, $check_token_query);

        if (mysqli_num_rows($check_token_query_run) > 0) {
            $row = mysqli_fetch_assoc($check_token_query_run);
            $email = $row['email'];

            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password in the users table
            $update_password_query = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
            $update_password_query_run = mysqli_query($con, $update_password_query);

            if ($update_password_query_run) {
                // Delete the token from the password_resets table
                $delete_token_query = "DELETE FROM password_resets WHERE token='$token'";
                mysqli_query($con, $delete_token_query);

                $_SESSION['status'] = "Password has been reset successfully.";
                header('Location: login.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Failed to reset password. Please try again.";
                header('Location: reset_password.php?token='.$token);
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid token.";
            header('Location: forgot_password.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Passwords do not match.";
        header('Location: reset_password.php?token='.$token);
        exit(0);
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    header('Location: forgot_password.php');
    exit(0);
}
?>
