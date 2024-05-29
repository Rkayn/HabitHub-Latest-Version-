<?php
session_start();
include 'config/dbcon.php';
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $habitId = $_POST['habit_id'];
    $habitFrequency = $_POST['habit_frequency'];
    $today = date('Y-m-d');

    // Check if the habit is already completed today
    $stmt = $pdo->prepare("SELECT * FROM habit_completion WHERE habit_id = ? AND category = ? AND date = ?");
    $stmt->execute([$habitId, $habitFrequency,$today]);
    $completed = $stmt->fetch();

    if ($completed) {
        echo json_encode(['status' => 'error', 'message' => 'Habit is already completed for today']);
    } else {
        // Mark habit as completed for today
        $stmt = $pdo->prepare("INSERT INTO habit_completion (id, habit_id, category, date) VALUES (?, ?, ?,?)");
        $stmt->execute([$user_id, $habitId, $habitFrequency, $today]);

        echo json_encode(['status' => 'success']);
    }
}*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $habitId = $_POST['habit_id'];
    $habitFrequency = $_POST['habit_frequency'];
    $today = date('Y-m-d');

    // Check if the habit is already completed today
    $stmt = $pdo->prepare("SELECT * FROM habit_completion WHERE habit_id = ? AND date = ?");
    $stmt->execute([$habitId, $today]);
    $completed = $stmt->fetch();

    if ($completed) {
        echo json_encode(['status' => 'error', 'message' => 'Habit is already completed for today']);
    } else {
        // Mark habit as completed for today
        $stmt = $pdo->prepare("INSERT INTO habit_completion (id, habit_id, category, date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $habitId, $habitFrequency, $today]);

        echo json_encode(['status' => 'success']); // Return valid JSON response
    }
}
?>
