<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #444;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            color: #333;
        }
        .login-container input:focus {
            border-color: #3c8dbc;
            box-shadow: 0 0 5px rgba(60, 141, 188, 0.5);
        }
        .login-container button {
            /* width: 100%;
            padding: 12px;
            background-color: #007BFF;
            border: none;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease; */

            width: 100%;
            padding: 12px;
            background-color: #3c8dbc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .login-container button:hover {
            background-color: #357ca5;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: #e74c3c;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <div class="error" id="error-message"></div>
        <label for="pb-no">PB No:</label>
        <input type="text" id="pb-no" placeholder="Enter PB No">
        <label for="password">Password:</label>
        <input type="password" id="password" placeholder="Enter Password">
        <p>Don't have an account? <a href="signinpage.html">Sign up</a></p>
        <button onclick="login()">Login</button>
    </div>
    <script>
        function login() {
            const pbNo = document.getElementById('pb-no').value;
            const password = document.getElementById('password').value;

            if (pbNo === '' || password === '') {
                document.getElementById('error-message').textContent = 'PB No and Password are required!';
                return;
            }

            // Example credentials
            const validPBNo = '12345';
            const validPassword = 'password';

            if (pbNo === validPBNo && password === validPassword) {
                alert('Login successful!');
            } else {
                document.getElementById('error-message').textContent = 'Invalid PB No or Password!';
            }
        }
    </script>
</body>
</html> -->

<!-- login.php -->
<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['pbno'])) {
    header("Location: attendance_dashboard.php");
    exit();
}

// Initialize error message
$error = '';
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance System</title>
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
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 15px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
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
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }
        .login-btn {
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
        .login-btn:hover {
            background-color: #5a0fbf;
        }
        .signup-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        .signup-link a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="pbno">PB Number</label>
                <input type="text" id="pbno" name="pbno" required pattern="\d{5}" title="PB number must be exactly 5 digits">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6" title="Password must be at least 6 characters long">
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>

</body>
</html>
