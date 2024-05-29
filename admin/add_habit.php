<?php
session_start();
include 'config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION["user_id"];
    $habit_name = $_POST['habit_name'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date']; 

    // Adjust the query to insert user_id instead of id
    $query = "INSERT INTO habits (id, habit_name, habit_frequency, habit_date) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isss", $user_id, $habit_name, $frequency, $start_date);

    if ($stmt->execute()) {
        // Fetch the last inserted habit to return it as part of the response
        $habit_id = $stmt->insert_id;
        $habit = [
            "habit_id" => $habit_id,
            "habit_name" => $habit_name,
            "habit_frequency" => $frequency,
            "habit_date" => $start_date
        ];
        echo json_encode(["status" => "success", "message" => "Habit added successfully", "habit" => $habit]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add habit."]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
