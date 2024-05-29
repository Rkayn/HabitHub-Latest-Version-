<?php
session_start();
include 'config/dbcon.php';

$user_id = $_SESSION['user_id'];

$start = $_GET['start'];
$end = $_GET['end'];

$query = "SELECT DATE(date) as date, COUNT(*) as count FROM habit_completion WHERE id = ? AND date BETWEEN ? AND ? GROUP BY DATE(date)";
$stmt = $con->prepare($query);
$stmt->bind_param("iss", $user_id, $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'date' => $row['date'],
        'count' => $row['count']
    ];
}

echo json_encode($events);
?>
