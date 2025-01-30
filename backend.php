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
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'save') {
            if (isset($_POST['browser_id']) && isset($_POST['expression']) && isset($_POST['result'])) {
                $browser_id = $_POST['browser_id'];
                $expression = $_POST['expression'];
                $result = $_POST['result'];

                $stmt = $conn->prepare("INSERT INTO calculations (browser_id, expression, result) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $browser_id, $expression, $result);

                if ($stmt->execute()) {
                    echo "Data saved successfully";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($action === 'loadHistory') {
            if (isset($_POST['browser_id']) && isset($_POST['offset'])) {
                $browser_id = $_POST['browser_id'];
                $offset = (int)$_POST['offset'];

                $stmt = $conn->prepare("SELECT expression FROM calculations WHERE browser_id = ? ORDER BY timestamp DESC LIMIT 1 OFFSET ?");
                $stmt->bind_param("si", $browser_id, $offset);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo json_encode(array("expression" => $row['expression']));
                } else {
                    echo json_encode(null);
                }
                $stmt->close();
            }
        } elseif ($action === 'getHistoryLength') {
            if (isset($_POST['browser_id'])) {
                $browser_id = $_POST['browser_id'];

                $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM calculations WHERE browser_id = ?");
                $stmt->bind_param("s", $browser_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                echo $row['count'];
                $stmt->close();
            }
        }
    }
}

$conn->close();
?>