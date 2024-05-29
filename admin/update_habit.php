<?php
header('Content-Type: application/json'); // Ensure the response is JSON
session_start();
include 'config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $habit_id = $_POST['habit_id'];
    $habit_name = $_POST['habit_name'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date'];

    $query = "UPDATE habits SET habit_name = ?, habit_frequency = ?, habit_date = ? WHERE habit_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssi", $habit_name, $frequency, $start_date, $habit_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Habit updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating habit."]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
