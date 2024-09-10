<?php
session_start();

// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = `emp`.`userdata`; // Replace with your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Retrieve and sanitize form inputs
$pbno = isset($_POST['pbno']) ? trim($_POST['pbno']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($pbno) || empty($password)) {
    header("Location: login.php?error=Please enter both PB number and password.");
    exit();
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT PBno, name, password FROM `emp`.`userdata` WHERE PBno = ?");
$stmt->bind_param("s", $pbno);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['userid'] = $user['PBno'];
        $_SESSION['pbno'] = $pbno;
        $_SESSION['name'] = $user['name'];
        
        // Redirect to attendance dashboard
        header("Location: attendance_dashboard.php");
        exit();
    } else {
        // Incorrect password
        header("Location: login.php?error=Incorrect password.");
        exit();
    }
} else {
    // User not found
    header("Location: login.php?error=PB number not found.");
    exit();
}

// Close connections
$stmt->close();
$conn->close();
?>
