<?php
include 'config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $habitId = $_POST['habit_id'];
    $today = date('Y-m-d');

    // Check if the habit is completed today
    $stmt = $pdo->prepare("SELECT * FROM habits_completion WHERE habit_id = ? AND completion_date = ?");
    $stmt->execute([$habitId, $today]);
    $completed = $stmt->fetch();

    if ($completed) {
        echo json_encode(['status' => 'success', 'completed' => true]);
    } else {
        echo json_encode(['status' => 'success', 'completed' => false]);
    }
}
?>
