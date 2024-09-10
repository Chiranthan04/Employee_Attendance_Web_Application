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
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$pbno = isset($_POST['pbno']) ? trim($_POST['pbno']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($name) || empty($pbno) || empty($password)) {
    header("Location: signup.php?error=Please fill in all fields.");
    exit();
}

// // Check if PB number is already taken
// $stmt = $conn->prepare("SELECT PRno FROM `emp`.`userdata` WHERE PBno = ?");
// $stmt->bind_param("i", $pbno);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     header("Location: signup.php?error=PB number is already registered.");
//     exit();
// }

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert new user into the database
$stmt = $conn->prepare("INSERT INTO `emp`.`userdata` (name, PBno, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $pbno, $hashed_password);

if ($stmt->execute()) {
    // Success: Redirect to login page with success message
    header("Location: signup.php?success=Account created successfully! Please log in.");
    exit();
} else {
    // Error: Redirect back to signup page with error message
    header("Location: signup.php?error=Error creating account PB Number already exists. Please try again.");
    exit();
}

// Close connections
$stmt->close();
$conn->close();
?>
