<?php
include 'config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $habitId = $_POST['habit_id'];
    $habitFrequency = $_POST['habit_frequency'];
    $isChecked = $_POST['is_checked'];

    // Update habit status in the database
    $stmt = $pdo->prepare("UPDATE habits SET completed = ? WHERE habit_id = ? AND habit_frequency = ?");
    $stmt->execute([$isChecked, $habitId, $habitFrequency]);

    // Send response
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
