<?php

$servername = "localhost";
$username = "calc_user";
$password = "verysavepw";
$dbname = "calculator_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$browser_id = $_POST['browser_id'];
$expression = $_POST['expression'];
$result = $_POST['result'];

$sql = "INSERT INTO calculations (browser_id, expression, result) VALUES ('$browser_id', '$expression', '$result')";

if ($conn->query($sql) === TRUE) {
  echo "Data saved successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>