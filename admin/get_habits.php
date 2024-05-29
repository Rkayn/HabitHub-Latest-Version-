<?php
include "config/dbcon.php";

/*if (!isset($_SESSION["user_id"])) {
    header('Location:login.php');
    die("User not logged in.");
}*/

$user_id = $_SESSION["user_id"]; // Ensure user is logged in and user_id is available

$query = "SELECT habit_id, habit_name, habit_frequency, habit_date FROM habits WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$habits = [];
while ($row = $result->fetch_assoc()) {
    $habits[] = $row;
}

//$stmt->close();
//$con->close();
?>
