<?php
session_start();
include('config/dbcon.php');

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);

    if ($password == $confirmpassword) {
        // Check if email already exists
        $check_email_query = "SELECT Email FROM users WHERE Email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con, $check_email_query);

        if (mysqli_num_rows($check_email_query_run) > 0) {
            $_SESSION['status'] = "Email already exists";
            header('Location: register.php');
            exit(0);
        } else {
            // Hash the password before saving to the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (Name, Phone, Email, Password, designation) VALUES ('$name', '$phone', '$email', '$hashed_password', '1')";
            $query_run = mysqli_query($con, $query);

            if ($query_run) {
                $_SESSION['status'] = "Registration Successful. You can now log in.";
                header('Location: login.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Registration Failed. Please try again.";
                header('Location: register.php');
                exit(0);
            }
        }
    } else {
        $_SESSION['status'] = "Password and Confirm Password do not match";
        header('Location: register.php');
        exit(0);
    }
}

include('includes/header.php');
?>

<div class="section d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 my-5">
                <?php
                if (isset($_SESSION['status'])) {
                    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                    echo '<strong>Hey!</strong> ' . $_SESSION['status'];
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['status']);
                }
                ?>
                <div class="card border-0 rounded shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3>Register</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="register.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" type="text" name="name" class="form-control" oninput="validate" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter your phone number"  maxlength="11" oninput="validateNumberInput(this)" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                            </div>
                            <div class="form-group mb-3">
                            <span class="toggle-password" style="transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye" id="togglePassword"></i>
                            </span>
                                <label for="password" class="form-label" >Password</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password (No Whitespaces)" minlength="10" required>
                            </div>
                            <div class="form-group mb-3">
                            <span class="toggle-confirm-password" style="transform: translateY(-50%); cursor: pointer;">
                                <i class="fas fa-eye" id="toggleconfirmPassword"></i>
                            </span>
                                <label for="confirmpassword" class="form-label" >Confirm Password</label>
                                <input id="confirmpassword" type="password" name="confirmpassword" class="form-control" placeholder="Confirm your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="register_btn" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">Already have an account? <a href="login.php">Login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<?php include('includes/script.php'); ?>

<script>
    function validateNumberInput(input) {
    // Remove any non-digit characters
    input.value = input.value.replace(/\D/g, '');
    
    // Limit to 11 digits
    if (input.value.length > 11) {
        input.value = input.value.slice(0, 11);
    }
}

$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const passwordFieldType = passwordField.attr('type');
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            passwordField.attr('type', 'password');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Toggle confirm password visibility
    $('#toggleconfirmPassword').click(function() {
        const passwordField = $('#confirmpassword');
        const passwordFieldType = passwordField.attr('type');
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            passwordField.attr('type', 'password');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });
});
//validations
$('#name').on('input', function() {
        var inputValue = $(this).val();
        var regex = /^[a-zA-Z]*$/; // Regular expression to allow only letters

    //    if (!regex.test(inputValue)) {
       //     $(this).val(inputValue.replace(/[^a-zA-Z]/g, '')); // Remove any characters other than letters
       // }
    });

    $('#password').on('input', function() {
        var inputValue = $(this).val();

        if (/\s/.test(inputValue)) {
            // If the input contains whitespace characters
            $(this).val(inputValue.replace(/\s/g, '')); // Remove whitespace characters
        }
    });
</script>

<!-- Custom CSS to enhance the look -->
<style>
    .section {
        background-color: #f8f9fa;
    }
    .card {
        background-color: white;
    }
    .card-header {
        border-bottom: none;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .form-control {
        border-radius: 0.25rem;
    }
    .btn-close {
        background-color: transparent;
        border: none;
    }
</style>
