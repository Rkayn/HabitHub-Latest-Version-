<?php
include 'db_connection.php';

function getHabitsWithStreaks($user_id) {
    global $conn;

    // Fetch habits
    $query = "SELECT id, habit_name, habit_frequency, habit_date FROM habits WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $habits = $result->fetch_all(MYSQLI_ASSOC);

    // Calculate streaks for each habit
    foreach ($habits as &$habit) {
        $habit_id = $habit['id'];

        // Fetch completion dates
        $query = "SELECT date FROM habit_completion WHERE habit_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $habit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $completion_dates = $result->fetch_all(MYSQLI_ASSOC);

        $streak = 0;
        $current_streak = true;
        $yesterday = new DateTime();

        foreach ($completion_dates as $date) {
            $completion_date = new DateTime($date['date']);
            $diff = $yesterday->diff($completion_date)->days;

            if ($diff == 1) {
                $streak++;
            } elseif ($diff > 1) {
                $current_streak = false;
                break;
            }
            $yesterday = $completion_date;
        }

        if ($current_streak) {
            $streak++;
        }

        $habit['streak'] = $streak;
    }

    return $habits;
}