<?php
session_start();
include 'config/dbcon.php';

$user_id = $_SESSION['user_id'];

// Fetch total habits
$totalHabitsQuery = "SELECT COUNT(*) as totalHabits FROM habits WHERE id = ?";
$stmt = $con->prepare($totalHabitsQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($totalHabits);
$stmt->fetch();
$stmt->close();

// Fetch completed habits today
$completedTodayQuery = "SELECT COUNT(*) as completedToday FROM habit_completion WHERE id = ? AND DATE(date) = CURDATE()";
$stmt = $con->prepare($completedTodayQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedToday);
$stmt->fetch();
$stmt->close();

// Completed habits this week
$completedWeekQuery = "SELECT COUNT(*) as completedWeek FROM habit_completion WHERE id = ? AND WEEK(date, 1) = WEEK(CURDATE(), 1) AND YEAR(date) = YEAR(CURDATE())";
$stmt = $con->prepare($completedWeekQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedWeek);
$stmt->fetch();
$stmt->close();

// Fetch completed habits this month
$completedMonthQuery = "SELECT COUNT(*) as completedMonth FROM habit_completion WHERE id = ? AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())";
$stmt = $con->prepare($completedMonthQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedMonth);
$stmt->fetch();
$stmt->close();


// Calculate completion percentage
$completionPercentageToday = ($totalHabits > 0) ? ($completedToday / $totalHabits) * 100 : 0;

// Fetch username
function getUsernameByUserId($user_id) {
    global $con;
    $query = "SELECT Name FROM users WHERE userId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
    return $username;
}
$username = getUsernameByUserId($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HabitHub</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="dist/css/adminlte.min.css?v=3.2.0">
<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<link rel="stylesheet" href="user_dashboard.css">
<!-- FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body class="layout-fixed" style="height: auto; background-color:white; overflow-x:hidden;">
<div class="wrapper-content" style="margin:10px; background-color:white;">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
    </nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
<a class="brand-link">
<img src="Heading-removebg-preview.png" alt="HabitHub" class="brand-image img-circle elevation-3" style="opacity: .8">
<span class="brand-text font-weight-light">Habit Hub</span>
</a>
<div class="sidebar">
<!-- User Panel -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex" style="text-align:center; align-items:center; align-content:center;">
    <div class="info" style="text-align:center;"> 
        <a href="#" class="d-block" data-toggle="modal" data-target="#profileModal" style="text-align:center;"><?php echo $username; ?></a>
       
    </div>
</div>
<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
<li class="nav-item">
<a href="user_dashboard.php" class="nav-link active">
<ion-icon class="nav-icon" name="grid"></ion-icon>
<p>Dashboard</p>
</a>
</li>
<li class="nav-item">
<a href="user_habits.php" class="nav-link">
<ion-icon class="nav-icon" name="clipboard"></ion-icon>
<p>Habits</p>
</a>
</li>
<li class="nav-item">
<a href="logout.php" class="nav-link">
<i><ion-icon class="nav-icon" name="exit"></ion-icon></i>
<p>Logout</p>
</a>
</li>
</ul>
</nav>
</div>
</aside>
<br><br>
<div class="content-wrapper" style="background-color:white; padding:5px;">
<div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1 class="m-0">Dashboard</h1>
</div>
<div class="col-sm-6">
</div>
</div>
</div>
</div>

<section class="wrapper" style="overflow-x:hidden;">
<div class="container-fluid">
<div class="row">
<div class="col-lg-3 col-6">
<div class="small-box bg-info">
<div class="inner">
<h3 id="totalHabits">
<?php echo $totalHabits; ?>
</h3>
<p>Number of Habits</p>
</div>
<div class="icon">
<i class="ion ion-bag"></i>
</div>
</div>
</div>

<div class="col-lg-3 col-6">
<div class="small-box bg-success">
<div class="inner" id="completedHabitsCount" class="alert alert-info" role="alert">
<h3><span id="completedCount"><?php echo $completedToday; ?></span></h3>
<p>Habits Completed Today</p>
</div>
<div class="icon">
<ion-ion name="checkmark"></ion-ion>
</div>
</div>
</div>

<div class="col-lg-3 col-6">
<div class="small-box bg-success">
<div class="inner" id="completedHabitsCount" class="alert alert-info" role="alert" style="background-color:blue;">
<h3><span id="completedCount"><?php echo $completedWeek; ?></span></h3>
<p>Habits Completed This Week</p>
</div>
<div class="icon">
<ion-ion name="checkmark"></ion-ion>
</div>
</div>
</div>

<div class="col-lg-3 col-6">
<div class="small-box bg-success">
<div class="inner" id="completedHabitsCount" class="alert alert-info" role="alert" style="background-color:orange;">
<h3><span id="completedCount"><?php echo $completedMonth; ?></span></h3>
<p>Habits Completed This Month</p>
</div>
<div class="icon">
<ion-ion name="checkmark"></ion-ion>
</div>
</div>
</div>

</div>
</div>
<!-- Additional Features -->
<div class="row">
<div class="col-lg-8">
<!-- Habit Tracking Section -->
<div class="card" style="height:550px;">
<div class="card-header">
<h3 class="card-title">Distribution of Completed Habits</h3>
</div>
<div class="card-body">
<canvas id="habitCompletionChart" style="height: 400px;"></canvas>
</div>
</div>
</div>

<div class="col-lg-4">
<!-- Calendar View Section -->
<div class="card" style="height:550px;">
    <div class="card-header">
        <h3 class="card-title">Calendar View</h3>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

</div>
</div>

</section>

</div>
</div>
<!-- Profile Modal -->
<div class="modal fade profile-modal" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="profileForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                    <span class="toggle-password" style="transform: translateY(-50%); cursor: pointer;">
              <i class="fas fa-eye" id="togglePassword"></i>
            </span>
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" >
                        
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            <button id="disableAccountButton" class="btn btn-danger" style="margin:0 0 5px 15px; width:150px;">Disable Account</button>
        </div>
    </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="plugins/sparklines/sparkline.js"></script>
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="dist/js/adminlte.js?v=3.2.0"></script>
<script src="dist/js/pages/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var habitChart;

    // Function to fetch and update the dashboard metrics
    function updateDashboard() {
        $.ajax({
            url: 'get_dashboard_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#totalHabits').text(data.totalHabits);
                $('#completedCount').text(data.completedToday);

                // Update the habit pie chart
                updateHabitChart(data.totalHabits, data.completedToday);
            },
            error: function(error) {
                console.error('Error fetching dashboard data:', error);
            }
        });
    }

    /*/ Function to update the habit pie chart
    function updateHabitChart(totalHabits, completedToday) {
        var ctx = document.getElementById('habitCompletionChart').getContext('2d');

        if (habitChart) {
            habitChart.destroy();
        }

        habitChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Remaining'],
                datasets: [{
                    data: [completedToday, totalHabits - completedToday],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }*/

    // Initial load
    updateDashboard();


    fetch('get_dashboard_data.php')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('habitCompletionChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Daily', 'Weekly', 'Monthly'],
                    datasets: [{
                        label: 'Habits Completed',
                        data: [data.dailyCount, data.weeklyCount, data.monthlyCount],
                        backgroundColor: ['green', 'blue', 'orange'],
                        hoverBackgroundColor: ['lightgreen', 'lightblue', '#FFCE56']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Habits Completed (Daily, Weekly, Monthly)'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

/*/ calendar
document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.getElementById('calendar');
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

    function daysInMonth(month, year) {
        return new Date(year, month + 1, 0).getDate();
    }

    function createCalendar(month, year) {
        // Clear the previous calendar
        calendar.innerHTML = '';

        const totalDays = daysInMonth(month, year);
        const startDay = new Date(year, month, 1).getDay();

        // Create days of the week
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        daysOfWeek.forEach(day => {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('day-name');
            dayDiv.textContent = day;
            calendar.appendChild(dayDiv);
        });

        // Create days with habit counts
        for (let day = 1; day <= totalDays; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('day');
            dayDiv.textContent = day;
            dayDiv.setAttribute('data-day', day);
            calendar.appendChild(dayDiv);
        }

        fetchHabitData(month + 1, year);
    }

    function fetchHabitData(month, year) {
        fetch(`get_habit_calendar.php?month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const dayDiv = document.querySelector(`.day[data-day="${item.day}"]`);
                    if (dayDiv) {
                        const habitCountDiv = document.createElement('div');
                        habitCountDiv.classList.add('habit-count');
                        habitCountDiv.textContent = `${item.count} `;
                        dayDiv.appendChild(habitCountDiv);
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    createCalendar(currentMonth, currentYear);
});*/


document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('get_habit_calendar.php?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                .then(response => response.json())
                .then(data => {
                    var events = data.map(item => ({
                        title: item.count + ' habits',
                        start: item.date
                    }));
                    successCallback(events);
                })
                .catch(error => failureCallback(error));
        }
    });

    calendar.render();
});


//profile
$(document).ready(function() {
    $('#profileModal').on('show.bs.modal', function() {
        // Fetch current user details
        $.ajax({
            url: 'get_profile_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#password').val(data.password);
                $('#profilePhotoPreview').attr('src', data.profilePhoto ? data.profilePhoto : 'dist/img/default-profile.jpg');
            },
            error: function(error) {
                console.error('Error fetching profile data:', error);
            }
        });
    });

    $('#profilePhoto').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#profilePhotoPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    $('#profileForm').submit(function(event) {
        event.preventDefault()  
        const formData = new FormData(this);
        
        $.ajax({
            url: 'user_update_profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Profile updated successfully:', response);

                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success',
                        text: 'Profile updated!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#profileModal').modal('hide');
                        updateUserProfileImage();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(error) {
                console.error('Error updating profile:', error);
            }
        });
        
    });

    function updateUserProfileImage() {
        $.ajax({
            url: 'get_profile_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#userProfileImage').attr('src', data.profilePhoto ? data.profilePhoto :$profilePhoto);
            },
            error: function(error) {
                console.error('Error fetching profile data:', error);
            }
        });
    }

    updateUserProfileImage();
    document.body.classList.add('sidebar-collapse');
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

   




});

document.getElementById('disableAccountButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, disable it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'disable_account.php';
                }
            })
        });


</script>
</body>
</html>
