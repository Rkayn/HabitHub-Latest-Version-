
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    
      

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> 
        
        <?php 
        if(isset( $_SESSION['auth']))
          {

               echo $_SESSION['auth_user']['user_Name']; 

          }
        else
        {
              echo "Not Logged In";
        }
        ?>

        </button>
        <ul class="dropdown-menu dropdown-menu-dark">
          <form action = "code.php" method="POST">
              <button type ="submit" name="logout_btn" class = "dropdown-item">Logout</button>
          </form>
        </ul>
    </div>
  </li>
      
          <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
