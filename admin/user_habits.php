
<?php
session_start();
include 'get_habits.php';
include 'config/dbcon.php';

$user_id = $_SESSION["user_id"];

// Get today's date
$today = date('Y-m-d');

// Fetch habits and their completion status for today
$stmt = $pdo->prepare("SELECT h.*, hc.date FROM habits h LEFT JOIN habit_completion hc ON h.habit_id = hc.habit_id AND hc.date = ? WHERE h.id = ?");
$stmt->execute([$today, $_SESSION['user_id']]);
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize counters for completed daily, weekly, and monthly habits
$completedDaily = 0;
$completedWeekly = 0;
$completedMonthly = 0;

// Count completed habits for each category and total habits
foreach ($habits as $habit) {
    if ($habit['habit_frequency'] === 'Daily' && $habit['date'] === $today) {
        $completedDaily++;
    } elseif ($habit['habit_frequency'] === 'Weekly' && $habit['date'] === $today) {
        $completedWeekly++;
    } elseif ($habit['habit_frequency'] === 'Monthly' && $habit['date'] === $today) {
        $completedMonthly++;
    }
}

// Calculate total habits for each category
$totalDaily = count(array_filter($habits, function($habit) { return $habit['habit_frequency'] === 'Daily'; }));
$totalWeekly = count(array_filter($habits, function($habit) { return $habit['habit_frequency'] === 'Weekly'; }));
$totalMonthly = count(array_filter($habits, function($habit) { return $habit['habit_frequency'] === 'Monthly'; }));

// Calculate progress for each category
$dailyProgress = $totalDaily > 0 ? ($completedDaily / $totalDaily) * 100 : 0;
$weeklyProgress = $totalWeekly > 0 ? ($completedWeekly / $totalWeekly) * 100 : 0;
$monthlyProgress = $totalMonthly > 0 ? ($completedMonthly / $totalMonthly) * 100 : 0;

function getUsernameByUserId($user_id) {
    global $con;
    global $username;
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HabitHub</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/fullcalendar/main.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="user_habits.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="layout-fixed" style="height: auto;">
    <div class="wrapper-habits" style="background-color: white; overflow-x:hidden;">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" role="button" style="margin:0 0 0 16px;"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a class="brand-link" style="text-decoration:none;">
                <img src="Heading-removebg-preview.png" alt="HabitHub" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Habit Hub</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    
                    <div class="info">
                    <a href="#" class="d-block" data-toggle="modal" data-target="#profileModal" style="text-align:center; text-decoration:none;"><?php echo $username; ?></a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="user_dashboard.php" class="nav-link ">
                            <ion-icon class="nav-icon" name="grid"></ion-icon>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user_habits.php" class="nav-link active">
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
        <div class="content-wrapper" style="background-color:white;">
            <div class="content">
                <main class="content-habits-list">
                    <section class="habit-list-header">
                        <h1>My Habits</h1>
                        <button class="add-habit-btn btn btn-primary" data-toggle="modal" data-target="#addHabitModal">+ Add New Habit</button>
                    </section>

                    <!-- Daily Habits Section -->
                        <h1>Daily</h1>
                        <div class="progress" id="daily-progress-bar">
                            <div class="progress-bar progress-bar bg-info" id="daily-progress-bar" role="progressbar" style="width: <?php echo $dailyProgress; ?>%" aria-valuenow="<?php echo $dailyProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $completedDaily; ?> / <?php echo $totalDaily; ?> Completed</div>
                        </div>
                        <?php foreach ($habits as $habit): ?>
                            <?php if ($habit['habit_frequency'] === 'Daily'): ?>
                                <!-- Habit card for daily habit -->
                                <section class="habit-group">
                                    <div class="habit-card">
                                    <input type="checkbox" class="habit-checkbox" 
       data-habit-id="<?php echo $habit['habit_id']; ?>" 
       data-habit-frequency="<?php echo $habit['habit_frequency']; ?>" 
       <?php echo $habit['date'] === $today ? 'checked disabled' : ''; ?>>

                                        <div class="habit-info">
                                            <div class="habit-name"><?php echo htmlspecialchars($habit['habit_name']); ?></div>
                                            
                                        </div>
                                        <button class="edit-btn btn"
                                            data-toggle="modal"
                                            data-target="#updateHabitModal"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($habit['habit_name']); ?>"
                                            data-frequency="<?php echo htmlspecialchars($habit['habit_frequency']); ?>"
                                            data-startdate="<?php echo $habit['habit_date']; ?>">
                                        Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="delete-btn btn" style="color:red;"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-toggle="modal"
                                            data-target="#deleteHabitModal">
                                        Delete
                                        </button>
                                    </div>
                                </section>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <!-- Weekly Habits Section -->
                        <h1>Weekly</h1>
                        <div class="progress">
                            <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo $weeklyProgress; ?>%" aria-valuenow="<?php echo $weeklyProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $completedWeekly; ?> / <?php echo $totalWeekly; ?> Completed</div>
                        </div>
                        <?php foreach ($habits as $habit): ?>
                            <?php if ($habit['habit_frequency'] === 'Weekly'): ?>
                                <!-- Habit card for weekly habit -->
                                <section class="habit-group">
                                    <div class="habit-card">
                                    <input type="checkbox" class="habit-checkbox" 
                                    data-habit-id="<?php echo $habit['habit_id']; ?>" 
                                    data-habit-frequency="<?php echo $habit['habit_frequency']; ?>" 
                                    <?php echo $habit['date'] === $today ? 'checked disabled' : ''; ?>>

                                        <div class="habit-info">
                                            <div class="habit-name"><?php echo htmlspecialchars($habit['habit_name']); ?></div>
                                        </div>
                                        
                                        <button class="edit-btn btn"
                                            data-toggle="modal"
                                            data-target="#updateHabitModal"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($habit['habit_name']); ?>"
                                            data-frequency="<?php echo htmlspecialchars($habit['habit_frequency']); ?>"
                                            data-startdate="<?php echo $habit['habit_date']; ?>">
                                        Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="delete-btn btn" style="color:red;"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-toggle="modal"
                                            data-target="#deleteHabitModal">
                                        Delete
                                        </button>
                                    </div>
                                </section>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <!-- Monthly Habits Section -->
                        <h1>Monthly</h1>
                        <div class="progress">
                            <div class="progress-bar progress-bar bg-info" role="progressbar" style="width: <?php echo $monthlyProgress; ?>%" aria-valuenow="<?php echo $monthlyProgress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $completedMonthly; ?> / <?php echo $totalMonthly; ?> Completed</div>
                        </div>
                        <?php foreach ($habits as $habit): ?>
                            <?php if ($habit['habit_frequency'] === 'Monthly'): ?>
                                <!-- Habit card for monthly habit -->
                                <section class="habit-group">
                                    <div class="habit-card">
                                        <input type="checkbox" class="habit-checkbox" data-habit-id="<?php echo $habit['habit_id']; ?>"  data-habit-frequency="<?php echo $habit['habit_frequency']; ?>" <?php echo $habit['date'] === $today ? 'checked disabled' : ''; ?>>
                                        <div class="habit-info">
                                            <div class="habit-name"><?php echo htmlspecialchars($habit['habit_name']); ?></div>
                                        </div>
                                        <button class="edit-btn btn"
                                            data-toggle="modal"
                                            data-target="#updateHabitModal"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($habit['habit_name']); ?>"
                                            data-frequency="<?php echo htmlspecialchars($habit['habit_frequency']); ?>"
                                            data-startdate="<?php echo $habit['habit_date']; ?>">
                                        Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="delete-btn btn" style="color:red;"
                                            data-id="<?php echo $habit['habit_id']; ?>"
                                            data-toggle="modal"
                                            data-target="#deleteHabitModal">
                                        Delete
                                        </button>
                                    </div>
                                </section>
                            <?php endif; ?>
                        <?php endforeach; ?>
                </main>
            </div>
        </div>
    </div>

<!-- Update Habit Modal -->
<div class="modal fade" id="updateHabitModal" tabindex="-1" role="dialog" aria-labelledby="updateHabitModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateHabitModalLabel">Update Habit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateHabitForm" action="update_habit.php" method="POST">
          <input type="hidden" id="updateHabitId" name="habit_id">
          <div class="form-group">
            <label for="updateHabitName">Habit Name</label>
            <input type="text" class="form-control" id="updateHabitName" name="habit_name" required>
          </div>
          <div class="form-group">
            <label for="updateHabitFrequency">Frequency</label>
            <select class="form-control" id="updateHabitFrequency" name="frequency">
              <option value="Daily">Daily</option>
              <option value="Weekly">Weekly</option>
              <option value="Monthly">Monthly</option>
            </select>
          </div>
          <div class="form-group">
            <label for="updateStartDate">Start Date</label>
            <input type="date" class="form-control" id="updateStartDate" name="start_date" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Habit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Habit Modal -->
<div class="modal fade" id="addHabitModal" tabindex="-1" role="dialog" aria-labelledby="addHabitModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHabitModalLabel">Add New Habit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addHabitForm" action="add_habit.php" method="POST">
          <div class="form-group">
            <label for="habitName">Habit Name</label>
            <input type="text" class="form-control" id="habitName" name="habit_name" required>
          </div>
          <div class="form-group">
            <label for="habitFrequency">Frequency</label>
            <select class="form-control" id="habitFrequency" name="frequency">
              <option value="Daily">Daily</option>
              <option value="Weekly">Weekly</option>
              <option value="Monthly">Monthly</option>
            </select>
          </div>
          <div class="form-group">
            <label for="startDate">Start Date</label>
            <input type="date" class="form-control" id="startDate" name="start_date" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Habit</button>
        </form>
      </div>
    </div>
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
        </div>
    </div>
</div>


    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    

<script>
      /*/ Function to mark habit as completed
function completeHabit(habitId, habitFrequency) {
    $.ajax({
        type: 'POST',
        url: 'update_habit_completion.php',
        data: { 
            habit_id: habitId,
            habit_frequency: habitFrequency // Pass habit_frequency along with habit_id
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Mark checkbox as checked and readonly
                $(`input[data-habit-id='${habitId}']`).prop('checked', true);
                document.getElementById("myCheck").disabled = true;
            } else {
                alert(response.message); // Display error message
            }
        },
        error: function(xhr, status, error) {
            alert('Error updating habit completion status: ' + error); // Display error message
        }
    });
}*/

function completeHabit(habitId) {
    var habitFrequency = $(`input[data-habit-id='${habitId}']`).data('habit-frequency');
    $.ajax({
        type: 'POST',
        url: 'update_habit_completion.php',
        data: { 
            habit_id: habitId,
            habit_frequency: habitFrequency // Include habit_frequency in the data
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Mark checkbox as checked and readonly
                $(`input[data-habit-id='${habitId}']`).prop('checked', true);
                $(`input[data-habit-id='${habitId}']`).attr('disabled', true); // Use attr to set disabled
                Swal.fire({
                          title: 'Great Job!',
                          text: 'Keep it up!',
                          icon: 'success',
                          confirmButtonText: 'OK'}).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }});
                    
            } else {
                alert(response.message); // Display error message
            }
        },
        error: function(xhr, status, error) {
            alert('Error updating habit completion status: ' + error); // Display error message
        }
    });
}


// Event listener for checkbox change
$('.habit-checkbox').on('change', function() {
    if ($(this).is(':checked')) {
        const habitId = $(this).data('habit-id');
        const habitFrequency = $(this).data('habit-frequency'); // Get habit_frequency from data attribute
        completeHabit(habitId, habitFrequency); // Pass habit_frequency to completeHabit function
    }
});

</script>

<script>
$(document).ready(function() {
    // Submit event listener for adding a habit
    $('#addHabitForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            type: 'POST',
            url: 'add_habit.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#addHabitModal').modal('hide'); // Hide the modal
                            addHabitToList(response.habit); // Add the habit to the list
                            $('#addHabitForm')[0].reset(); // Clear the form inputs
                            location.reload();
                        }
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
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: ' + error + ' - ' + xhr.responseText,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Function to dynamically add the new habit to the list
    function addHabitToList(habit) {
        const habitItem = `
            <div>
                <label>
                    <input type="checkbox" name="habit_${habit.habit_id}" readonly>
                    ${habit.habit_name}
                </label>
            </div>
        `;

        if (habit.habit_frequency === 'Daily') {
            $('#dailyHabits').append(habitItem);
        } else if (habit.habit_frequency === 'Weekly') {
            $('#weeklyHabits').append(habitItem);
        } else if (habit.habit_frequency === 'Monthly') {
            $('#monthlyHabits').append(habitItem);
        }
    }
});



      // update script
document.querySelectorAll('.edit-btn').forEach(button => {
          button.addEventListener('click', function() {
              const habitId = this.getAttribute('data-id');
              const habitName = this.getAttribute('data-name');
              const habitFrequency = this.getAttribute('data-frequency');
              const habitStartDate = this.getAttribute('data-startdate');
  
              document.getElementById('updateHabitId').value = habitId;
              document.getElementById('updateHabitName').value = habitName;
              document.getElementById('updateHabitFrequency').value = habitFrequency;
              document.getElementById('updateStartDate').value = habitStartDate;
          });
      });
  
      // Submit event listener for updating a habit
$('#updateHabitForm').on('submit', function(e) {
          e.preventDefault();
  
          $.ajax({
              type: 'POST',
              url: 'update_habit.php',
              data: $(this).serialize(),
              dataType: 'json',
              success: function(response) {
                  if (response.status === 'success') {
                      Swal.fire({
                          title: 'Success!',
                          text: response.message,
                          icon: 'success',
                          confirmButtonText: 'OK'
                      }).then((result) => {
                          if (result.isConfirmed) {
                              $('#updateHabitModal').modal('hide');
                              location.reload();
                          }
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
              error: function(xhr, status, error) {
                  Swal.fire({
                      title: 'Error!',
                      text: 'Error: ' + error + ' - ' + xhr.responseText,
                      icon: 'error',
                      confirmButtonText: 'OK'
                  });
              }
          });
});

  //del script
$(document).ready(function() {
    console.log('Document ready function executing'); // Add this line
    
    var habitIdToDelete;

    // Trigger delete modal
    $('.delete-btn').on('click', function() {
        console.log('Delete button clicked'); // Add this line
        
        habitIdToDelete = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete.php',
                    type: 'POST',
                    data: { habit_id: habitIdToDelete },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            response,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the habit.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
$(document).ready(function() {
            // Variable to store the habit ID to delete
            var habitIdToDelete;

            // Event listener for delete buttons
            $('.delete-btn').on('click', function() {
                habitIdToDelete = $(this).data('id');
                console.log('Delete button clicked. Habit ID:', habitIdToDelete); // Debug log

                // Trigger SweetAlert2 modal
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform AJAX request to delete the habit
                        $.ajax({
                            url: 'delete.php',
                            type: 'POST',
                            data: { habit_id: habitIdToDelete },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response,
                                    'success'
                                ).then(() => {
                                    location.reload(); // Reload the page to reflect changes
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the habit.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });



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

    //sidebar
    document.body.classList.add('sidebar-collapse');
});



</script>


</body>
</html>