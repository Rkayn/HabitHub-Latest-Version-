<?php
session_start();
include('config/dbcon.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $query = "SELECT * FROM password_resets WHERE token='$token' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_assoc($query_run);
        $email = $user['email'];
    } else {
        $_SESSION['status'] = "Invalid token.";
        header('Location: forgot_password.php');
        exit(0);
    }
}

if (isset($_POST['reset_password'])) {
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($new_password == $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $update_password_query = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
        $query_run = mysqli_query($con, $update_password_query);

        if ($query_run) {
            // Delete the token from the password_resets table
            $delete_token_query = "DELETE FROM password_resets WHERE token='$token'";
            mysqli_query($con, $delete_token_query);

            $_SESSION['status'] = "Password reset successful. You can now log in.";
            echo "Success!";
            header('Location: login.php');
            exit(0);
        } else {
            $_SESSION['status'] = "Something went wrong. Please try again.";
            header('Location: reset_password.php?token=' . $token);
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Passwords do not match.";
        echo "Passwords do not match";
        header('Location: reset_password.php?token=' . $token);
        exit(0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php
        if (isset($_SESSION['status'])) {
            echo '<div class="alert">' . $_SESSION['status'] . '</div>';
            unset($_SESSION['status']);
        }
        ?>
        <form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
</body>
</html>

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width:600px;
            max-width: 1000px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input {
            width: 96%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            background-color: #f44336;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>