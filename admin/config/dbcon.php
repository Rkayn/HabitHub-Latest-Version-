<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "admin_db";

//connection
$con = mysqli_connect("$host","$username","$password","$database");

//check connection
if (!$con)
{
    header("Location: ..errors/db.php");
    die();
}
else{
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
  }
?>