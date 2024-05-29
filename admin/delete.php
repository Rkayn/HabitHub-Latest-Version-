<?php
/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['habit_id'])) {
        $habit_id = $_POST['habit_id'];

        /*$query = "DELETE FROM habits AND habit_completion WHERE habit_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $habit_id);

        $query = "DELETE FROM habits WHERE habit_id = $habit_id";
        $query2 = "DELETE FROM habit_completion WHERE habit_id = $habit_id";
   con_multi_query($con, $query);    
   con_multi_query($con, $query2);

        if ($stmt->execute()) {
            echo "Habit deleted successfully.";
        } else {
            echo "Error deleting habit.";
        }

        $stmt->close();
    } else {
        echo "Habit ID not provided.";
    }
} else {
    echo "Invalid request method.";
}*/

// Database connection parameters
include 'config/dbcon.php';
// Ensure habit_id is an integer
$habit_id = intval($_POST['habit_id']);

$sql1 = "DELETE FROM habits WHERE habit_id = ?";
$sql2 = "DELETE FROM habit_completion WHERE habit_id = ?";

if ($stmt1 = $con->prepare($sql1)) {
    $stmt1->bind_param("i", $habit_id);
    $stmt1->execute();
    $stmt1->close();
} else {
    // Handle error
    echo "Error preparing statement 1: " . $con->error;
}

if ($stmt2 = $con->prepare($sql2)) {
    $stmt2->bind_param("i", $habit_id);
    $stmt2->execute();
    $stmt2->close();
} else {
    // Handle error
    echo "Error preparing statement 2: " . $con->error;
}

// Close the connection
$con->close();
?>
