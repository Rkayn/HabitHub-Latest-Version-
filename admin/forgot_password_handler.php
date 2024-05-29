<?php
session_start();
include('config/dbcon.php');

if (isset($_POST['forgot_submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists in the database
    $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $user = mysqli_fetch_assoc($check_email_query_run);
        $token = bin2hex(random_bytes(50));

        // Insert token into the password_resets table
        $insert_token_query = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
        $query_run = mysqli_query($con, $insert_token_query);

        if ($query_run) {
            $reset_link = "http://localhost/HabitHubv2/admin/reset_password.php?token=$token";

            // Send email using PHP's mail() function
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Hi, click on the link to reset your password: <a href='$reset_link'>Reset Password</a>";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@yourdomain.com" . "\r\n";

            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['status'] = "Password reset link has been sent to your email.";
                header('Location: the_email_sent.html');
                exit(0);
            } else {
                $_SESSION['status'] = "Failed to send email.";
                header('Location: forgot_password.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Something went wrong. Please try again.";
            header('Location: forgot_password.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Email not found.";
        header('Location: forgot_password.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = "Invalid request.";
    header('Location: forgot_password.php');
    exit(0);
}
