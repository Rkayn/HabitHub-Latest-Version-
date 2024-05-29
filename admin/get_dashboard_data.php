<?php
// Include necessary files and start session if required
session_start();
include 'config/dbcon.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return an error response
    $response = array(
        'status' => 'error',
        'message' => 'User is not logged in'
    );
    echo json_encode($response);
    exit; // Stop further execution
}

// Fetch the user's ID from the session
$user_id = $_SESSION['user_id'];

// Query to fetch the number of habits completed today
$completedTodayQuery = "
    SELECT COUNT(*) as completedToday 
    FROM habit_completion 
    WHERE id = ? 
    AND category = 'Daily'
    
";
//AND DATE(date) = CURDATE()
//$completedTodayQuery = "SELECT COUNT(*) as completedToday FROM habit_completion WHERE id = ? AND DATE(date) = CURDATE()
$stmt = $con->prepare($completedTodayQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedToday);
$stmt->fetch();
$stmt->close();

// Query to fetch the number of habits completed in the current week
$completedWeeklyQuery = "
    SELECT COUNT(*) as completedWeekly
    FROM habit_completion
    WHERE id = ?
    AND category = 'Weekly'
    AND YEARWEEK(date) = YEARWEEK(CURDATE())
";
$stmt = $con->prepare($completedWeeklyQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedWeekly);
$stmt->fetch();
$stmt->close();

// Query to fetch the number of habits completed in the current month
$completedMonthlyQuery = "
    SELECT COUNT(*) as completedMonthly
    FROM habit_completion
    WHERE id = ?
    AND category = 'Monthly'
";

//AND YEAR(date) = YEAR(CURDATE())
//AND MONTH(date) = MONTH(CURDATE())

$stmt = $con->prepare($completedMonthlyQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($completedMonthly);
$stmt->fetch();
$stmt->close();

// Construct the response array
$response = array(
    'dailyCount' => $completedToday,
    'weeklyCount' => $completedWeekly,
    'monthlyCount' => $completedMonthly
);

// Encode the response array as JSON and echo it
echo json_encode($response);
?>
