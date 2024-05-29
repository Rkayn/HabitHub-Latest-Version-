<?php
session_start();
include 'config/dbcon.php';

$user_id = $_SESSION["user_id"];
$current_date = date('Y-m-d');

// Query to count the habits completed today
$query = "SELECT COUNT(*) AS completed_count FROM habit_completion WHERE id = ? AND DATE(date) = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$stmt->bind_result($completed_count);
$stmt->fetch();

if ($completed_count !== null) {
    echo json_encode(["status" => "success", "data" => ["completed_count" => $completed_count]]);
} else {
    echo json_encode(["status" => "error", "message" => "Unable to fetch completed habits count."]);
}

$stmt->close();
$con->close();
?>
