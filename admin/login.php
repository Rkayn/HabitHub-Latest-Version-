<?php
session_start();
include('includes/header.php');
if(isset($_SESSION['auth']))
{
    $_SESSION['status'] = "You are already logged in";
    header('Location: index.php');
    exit(0);
}
?>

<div class="section d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 my-5">
                <?php 
                if(isset($_SESSION['auth_status'])) {
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['auth_status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    unset($_SESSION['auth_status']);
                }
                ?>

                <?php
                include('message.php');
                ?>
                <div class="card border-0 rounded shadow-lg">
                    
                    <div class="card-header bg-primary text-white text-center py-3">
                    <a href="contact_support.html"><button id="contactSupportBtn" class="icon-btn" style="background-color:transparent; left:150px; border:transparent; color:white;">
        <i class="fas fa-question-circle"></i> <!-- Font Awesome icon -->
    </button></a>
                        <h3>Login</h3>
                        
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <form action="logincode.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="login_btn" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                    <div class="large"><a href="register.php">Sign up</a></div>
                    <a href="forgot_password.php"><div class="small">Forgot Password</a></div>
                    
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    
</div>




<?php include('includes/script.php');?>

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

<script>



</script>