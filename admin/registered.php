<?php
include('authentication.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/dbcon.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!--User-Modal -->
<div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="">Name</label><br>
            <input type="text" name="name" class="form-control" placeholder="Name">
          </div>
          <div class="form-group">
            <label for="">Phone Number</label><br>
            <input type="number" name="phone" class="form-control" placeholder="Phone Number">
          </div>
          <div class="form-group">
            <label for="">Email Address</label><br>
            <input type="email" name="email" class="form-control email_id" placeholder="Email">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Password</label><br>
                <input type="password" name="password" class="form-control" placeholder="Password" required minlength="10">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Confirm Password</label><br>
                <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="adduser" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Registered Users</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!--/.content-header-->
<section class="content">
  <div class="container">
    <div class="row">
      <div class="col-mid-12">
        <?php
        if (isset($_SESSION['status'])) {
          echo "<h4>" . $_SESSION['status'] . "</h4>";
          unset($_SESSION['status']);
        }
        ?>
      </div>
    </div>
  </div>
</section>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Registered Users</h3>
    <a href="#" data-toggle="modal" data-target="#AddUserModal" class="btn btn-primary btn-sm float-right">Add Users</a>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>UserID</th>
          <th>Name</th>
          <th>Phone Number</th>
          <th>Email</th>
          <th>Role</th>
          <th>Active/Inactive</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $query = "SELECT * FROM users";
        $query_run = mysqli_query($con, $query);
        if (mysqli_num_rows($query_run) > 0) {
          foreach ($query_run as $row) {
        ?>
          <tr>
            <td><?php echo $row['UserID']; ?></td>
            <td><?php echo $row['Name']; ?></td>
            <td><?php echo $row['Phone']; ?></td>
            <td><?php echo $row['Email']; ?></td>
            <td>
              <?php
              if ($row['Role'] == '0') {
                echo "User";
              } elseif ($row['Role'] == '1') {
                echo "Admin";
              } else {
                echo "Invalid User";
              }
              ?>
            </td>
            <td>
              <?php 
              $designation = $row['designation'];
              $UserID = $row['UserID'];
              if ($designation == 1) {
                echo "<a href='deactivate.php?id=".$UserID."'><button type='button' class='btn btn-outline-danger'>Deactivate</button></a>";
              } else if ($designation == 0) {
                echo "<a href='activate.php?id=".$UserID."'><button type='button' class='btn btn-outline-primary'>Activate</button></a>";
              }
              ?>
            </td>
            <td>
              <a href="registered-edit.php?user_id=<?php echo $row['UserID'];?>" class="btn btn-info btn-sm">Edit</a>
          </tr>
        <?php
          }
        } else {
        ?>
          <tr>
            <td colspan="7">No Record Found</td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</div>

<?php include('includes/script.php'); ?>
<?php include('includes/footer.php'); ?>
