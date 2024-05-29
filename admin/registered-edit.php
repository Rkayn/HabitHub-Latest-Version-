<?php
include('authentication.php');

include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/dbcon.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit - Registered Users</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
</div>


<!--/.content-header-->


<section class="content">
<div class="container">
    <div class = "row">
        <div class="col-mid-12">
        <?php
            if(isset($_SESSION['status']))
            {
                echo"<h4>".$_SESSION['status']."</h4>";
                unset($_SESSION['status']);
            }
          ?>
          </div>
        </div>
    </div>
          </section>
                  <div class="card">
                    <div class="card-header">
                       <h3 class="card-title">Edit - Registered Users</h3>
                          <a href="registered.php" class="btn btn-danger btn-sm float-right">BACK</a>
                        </h3>
                      </div>
                      </table>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <form action="code.php" method="POST">
                                <div class="modal-body">
                                    <?php
                                    if(isset($_GET['user_id']))
                                    {
                                        $user_id = $_GET['user_id'];
                                        $query = "SELECT * FROM users WHERE UserID = '$user_id' LIMIT 1 ";
                                        $query_run = mysqli_query($con, $query);
                                        if(mysqli_num_rows($query_run) > 0 )
                                        {
                                            foreach($query_run as $row)
                                            {
                                                ?>      
                                                     <input type="hidden" name="user_id" value="<?php echo $row['UserID'] ?>">
                                                     <div class="form-group">
                                                        <label for="">Name</label><br>
                                                        <input type="text" name="name" value = "<?php echo $row['Name']?>" class = "form-control" placeholder="Name">
                                                         </div>
                                                        <div class="form-group">
                                                        <label for="">Phone Number</label><br>
                                                         <input type="text" name="phone" value = "<?php echo $row['Phone']?>" class = "form-control" placeholder="Phone Number">
                                                        </div>
                                                         <div class="form-group">
                                                        <label for="">Email Address</label><br>
                                                        <input type="email" name="email" value = "<?php echo $row['Email']?>" class = "form-control" placeholder="Email">
                                                        </div>
                                                         <div class="form-group">
                                                        <label for="">Password</label><br>
                                                        <input type="password" name="password"  value = "<?php echo $row['Password']?>" class = "form-control" placeholder="Password" required minlength="10">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Confirm Password</label><br>
                                                        <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password">
                                                    </div>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            echo "<h4> No Record Found.! </h4>";
                                        }                  
                                    }                  
                                    ?>                                
                                </div>
                                <div class="modal-footer">              
                                    <button type="submit" name="UpdateUser" class="btn btn-info">Update</button>
                                </div>
                            </form>
                        </div>


                    </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
        </section>
</div>

<?php include('includes/footer.php');?>

<?php include('includes/script.php');?>
