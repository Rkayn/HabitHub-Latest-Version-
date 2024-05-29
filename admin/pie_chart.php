<?php
session_start();
include 'config/dbcon.php';

$id = $_SESSION["user_id"];

// Queries to fetch counts for daily, weekly, and monthly completed habits
$dailyQuery = "
    SELECT COUNT(*) AS count 
    FROM habit_completion 
    JOIN habits ON habit_completion.habit_id = habits.id
    WHERE habit_completion.id = ? 
    AND DATE(habit_completion.date) = CURDATE() 
    AND habits.habit_frequency = 'daily'";

$weeklyQuery = "
    SELECT COUNT(*) AS count 
    FROM habit_completion 
    JOIN habits ON habit_completion.habit_id = habits.id
    WHERE habit_completion.id = ? 
    AND WEEK(habit_completion.date) = WEEK(CURDATE()) 
    AND habits.habit_frequency = 'weekly'";

$monthlyQuery = "
    SELECT COUNT(*) AS count 
    FROM habit_completion 
    JOIN habits ON habit_completion.habit_id = habits.id
    WHERE habit_completion.id = ? 
    AND MONTH(habit_completion.date) = MONTH(CURDATE()) 
    AND habits.habit_frequency = 'monthly'";

// Execute queries and fetch results
$stmt = $con->prepare($dailyQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($dailyCount);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare($weeklyQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($weeklyCount);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare($monthlyQuery);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($monthlyCount);
$stmt->fetch();
$stmt->close();

$con->close();

// Return the data as JSON
echo json_encode([
    "dailyCount" => $dailyCount,
    "weeklyCount" => $weeklyCount,
    "monthlyCount" => $monthlyCount
]);