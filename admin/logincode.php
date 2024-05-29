<?php 

session_start();
include('config/dbcon.php');

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $log_query = "SELECT * FROM users WHERE Email = '$email' LIMIT 1";
    $log_query_run = mysqli_query($con, $log_query);

    if (mysqli_num_rows($log_query_run) > 0) {
        $row = mysqli_fetch_array($log_query_run);
        
        // Use password_verify to check hashed password
        if (password_verify($password, $row['Password'])) {
            if ($row['designation'] == 1) { // 1 means active, 0 means inactive
                $user_UserID = $row['UserID'];
                $user_Name = $row['Name'];
                $user_Phone = $row['Phone'];
                $user_Email = $row['Email'];
                $Role = $row['Role'];
                
                $_SESSION['auth'] = $Role;
                $_SESSION['user_id'] = $user_UserID; // Set user_id in the session
                $_SESSION['auth_user'] = [
                    'user_Name' => $user_Name,
                    'user_Phone' => $user_Phone,
                    'user_UserID' => $user_UserID,
                    'user_Email' => $user_Email
                ];

                $_SESSION['status'] = "Logged in Successfully";
                header('Location: index.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Your account is deactivated. Please contact support.";
                header('Location: login.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid Email or Password";
            header('Location: login.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Invalid Email or Password";
        header('Location: login.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = "Access Denied";
    header('Location: login.php');
    exit(0);
}
?>





/*session_start();
include('config/dbcon.php');

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $log_query = "SELECT * FROM users WHERE Email = '$email' LIMIT 1";
    $log_query_run = mysqli_query($con, $log_query);

    if (mysqli_num_rows($log_query_run) > 0) {
        $row = mysqli_fetch_array($log_query_run);
        
        // Use password_verify to check hashed password
        if (password_verify($password, $row['Password'])) {
            if ($row['designation'] == 1) { // 1 means active, 0 means inactive
                $user_UserID = $row['UserID'];
                $user_Name = $row['Name'];
                $user_Phone = $row['Phone'];
                $user_Email = $row['Email'];
                $Role = $row['Role'];
                
                $_SESSION['auth'] = "$Role";
                $_SESSION['auth_user'] = [
                    'user_Name' => $user_Name,
                    'user_Phone' => $user_Phone,
                    'user_UserID' => $user_UserID,
                    'user_Email' => $user_Email
                ];

                $_SESSION['status'] = "Logged in Successfully";
                header('Location: index.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Your account is deactivated. Please contact support.";
                header('Location: login.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid Email or Password";
            header('Location: login.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Invalid Email or Password";
        header('Location: login.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = "Access Denied";
    header('Location: login.php');
    exit(0);
}
?>
*/



/*
session_start();
include('config/dbcon.php');

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $log_query = "SELECT * FROM users WHERE Email = '$email' LIMIT 1";
    $log_query_run = mysqli_query($con, $log_query);

    if (mysqli_num_rows($log_query_run) > 0) {
        $row = mysqli_fetch_array($log_query_run);
        
        // Assuming passwords are stored as plain text for now
        // Uncomment the line below and comment out the next if passwords are hashed
        // if (password_verify($password, $row['Password'])) {
        if ($password == $row['Password']) {
            if ($row['designation'] == 1) { // 1 means active, 0 means inactive
                $user_UserID = $row['UserID'];
                $user_Name = $row['Name'];
                $user_Phone = $row['Phone'];
                $user_Email = $row['Email'];
                $Role = $row['Role'];
                
                $_SESSION['auth'] = "$Role";
                $_SESSION['auth_user'] = [
                    'user_Name' => $user_Name,
                    'user_Phone' => $user_Phone,
                    'user_UserID' => $user_UserID,
                    'user_Email' => $user_Email
                ];

                $_SESSION['status'] = "Logged in Successfully";
                header('Location: index.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Your account is deactivated. Please contact support.";
                header('Location: login.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid Email or Password";
            header('Location: login.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "Invalid Email or Password";
        header('Location: login.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = "Access Denied";
    header('Location: login.php');
    exit(0);
}
?>

/*
    session_start();
    include('config/dbcon.php');

    if(isset($_POST['login_btn']))
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $log_query = " SELECT * FROM users WHERE Email = '$email' AND password = '$password' LIMIT 1 ";
            $log_query_run = mysqli_query($con, $log_query);


                if(mysqli_num_rows($log_query_run) > 0)
                    {
                        foreach($log_query_run as $row)
                        {
                            $user_UserID = $row['UserID'];
                            $user_Name = $row['Name'];
                            $user_Phone = $row['Phone'];
                            $user_Email = $row['Email'];
                            $Role = $row['Role'];
                              
                        }
                            $_SESSION['auth'] = "$Role";
                            $_SESSION['auth_user'] = [
                                'user_Name'=>$user_Name,
                                'user_Phone'=>$user_Phone,
                                'user_UserID'=>$user_UserID,
                                'user_Email'=>$user_Email

                            ];

                            $_SESSION['status'] =  "Logged in Successfully";
                            header('Location: index.php'); 

                    }
                else
                    {
                        $_SESSION['status'] =  "Invalid Email or Password";
                        header('Location: login.php');           
                    }    
        }
    else
        {
            $_SESSION['status'] =  "Access Denied";
            header('Location: login.php');           
        }


*/
?>
