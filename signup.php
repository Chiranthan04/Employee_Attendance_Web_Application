<!-- signup.php -->

<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['pbno'])) {
    header("Location: attendance_dashboard.php");
    exit();
}

// Initialize error and success messages
$error = '';
$success = '';
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
if (isset($_GET['success'])) {
    $success = htmlspecialchars($_GET['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Attendance System</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <!-- CSS Styles -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .signup-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 15px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .signup-container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #6a11cb;
        }
        .error-message,
        .success-message {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .error-message {
            color: #e74c3c;
        }
        .success-message {
            color: #27ae60;
        }
        .signup-btn {
            width: 100%;
            padding: 15px;
            background-color: #6a11cb;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .signup-btn:hover {
            background-color: #5a0fbf;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        .login-link a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="signup_process.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="pbno">PB Number</label>
                <input type="text" id="pbno" name="pbno" required pattern="\d{5}" title="PB number must be exactly 5 digits">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6" title="Password must be at least 6 characters long">
            </div>
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>

</body>
</html>
