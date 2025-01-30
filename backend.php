<?php

$servername = "localhost";
$username = "calc_user";
$password = "verysavepw";
$dbname = "calculator_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['browser_id']) && isset($_POST['expression']) && isset($_POST['result'])) {
        $browser_id = $_POST['browser_id'];
        $expression = $_POST['expression'];
        $result = $_POST['result'];

        $sql = "INSERT INTO calculations (browser_id, expression, result) VALUES ('$browser_id', '$expression', '$result')";

        if ($conn->query($sql) === TRUE) {
            echo "Data saved successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['browser_id'])) {
        $browser_id = $_GET['browser_id'];

        $sql = "SELECT expression, result FROM calculations WHERE browser_id = '$browser_id' ORDER BY id DESC";
        $result = $conn->query($sql);

        $history = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $history[] = $row['expression'] . ' = ' . $row['result'];
            }
        }

        echo json_encode($history);
    }
}

$conn->close();
?>