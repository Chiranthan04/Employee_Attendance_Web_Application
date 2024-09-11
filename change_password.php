<?php
session_start();
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = `emp`.`userdata`; // Replace with your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

$error= '';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pbno = $_SESSION['pbno'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: change_password.php?error=All fields are required.");
        exit();    
    }

    if ($new_password !== $confirm_password) {
        header("Location: change_password.php?error=New password and confirm password do not match.");
        exit(); 
    }


    // Fetch the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM `emp`.`userdata` WHERE pbno = ?");
    $stmt->bind_param("s", $pbno);
    $stmt->execute();
    $stmt->bind_result($stored_password_hash);
    $stmt->fetch();
    $stmt->close();

    // Verify the old password
    if (!password_verify($old_password, $stored_password_hash)) {
        $error='Old password is incorrect.';  
    }else{
    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE `emp`.`userdata` SET password = ? WHERE pbno = ?");
    $stmt->bind_param("ss", $new_password_hash, $pbno);
    if ($stmt->execute()) {
        header("Location: attendance_dashboard.php?passchangesuccess=Password Changed Successfully!!");
        exit();
    } else {
        $error='An error occurred. Please try again.';
    }

    $stmt->close();
    $conn->close();
} }

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .change-password-container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
        }
        .change-password-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .change-password-btn {
            width: 100%;
            padding: 12px;
            background-color: #6a11cb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }
        .change-password-btn:hover {
            background-color: #5a0fbf;
        }
        .message {
            margin-top: 25px;
            text-align: center;
            font-size: 16px;
            margin-bottom: 25px;
        }
        .message.success {
            color: #27ae60;
        }
        .message.error {
            color: #e74c3c;
        }
    </style>
</head>
<body>

<div class="change-password-container">
    <h2>Change Password</h2>
    <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
    <form action="change_password.php" method="post">

        <div class="form-group">
            <label for="old_password">Old Password:</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>
        <button type="submit" class="change-password-btn">Change Password</button>
    </form>
</div>

</body>
</html>
