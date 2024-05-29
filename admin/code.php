<?php

session_start();
include('authentication.php');
include('config/dbcon.php');

if(isset($_POST['logout_btn']))
  {
    session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);

    $_SESSION['status'] = "Logged out Successfully";
    header('Location: login.php');
    exit(0);

  }
  

if(isset($_POST['adduser']))

 {

  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmpassword = $_POST['confirmpassword'];

  if($password == $confirmpassword)
    {

        $checkemail = "SELECT Email FROM users WHERE Email = '$email' ";
        $checkemail_run = mysqli_query($con, $checkemail);  

        if(mysqli_num_rows($checkemail_run) > 0)
            {
              //Taken already exist
              $_SESSION['status'] = "Email ID is already taken";
              header("Location: registered.php");
              exit;
            }
            else
            {
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
              $query = "INSERT INTO users (Name, Phone, Email, Password, designation) VALUES ('$name', '$phone', '$email', '$hashed_password', '1')";
              $query_run = mysqli_query($con, $query);

                  if($query_run)
              {
                      $_SESSION['status'] = "User Added Successfully";
                      header("Location: registered.php");
                      
               }
              
                  else
              {
                      $_SESSION['status'] = "User Registration Failed";
                      header("Location: registered.php");
               }  
              }   
            }
          

  else
    {
      $_SESSION['status'] = "Password and Confirm Password doesn't match";
      header("Location: registered.php");
    }
              
}



if (isset($_POST['UpdateUser'])) {
  $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $phone = mysqli_real_escape_string($con, $_POST['phone']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);

  if ($password == $confirmpassword) {
      // Hash the new password before updating the database
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $update_query = "UPDATE users SET Name='$name', Phone='$phone', Email='$email', Password='$hashed_password' WHERE UserID='$user_id'";
      $update_query_run = mysqli_query($con, $update_query);

      if ($update_query_run) {
          $_SESSION['status'] = "Account Updated Successfully";
          header('Location: registered.php?user_id=' . $user_id);
          exit(0);
      } else {
          $_SESSION['status'] = "Account Update Failed. Please try again.";
          header('Location: registered.php?user_id=' . $user_id);
      }
  } else {
      $_SESSION['status'] = "Password and Confirm Password do not match";
      header('Location: registered.php?user_id=' . $user_id);
  }
}

?>