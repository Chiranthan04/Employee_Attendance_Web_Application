<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['pbno'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = `emp`.`attendance_data`; // Replace with your database name
// Retrieve user information from session
$name = $_SESSION['name'];
$pbno = $_SESSION['pbno'];




// Initialize success or error messages
$success = '';
$error = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database configuration
  $servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = `emp`.`attendance_data`; // Replace with your database name

    // Create a new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Retrieve and sanitize form inputs
    $year = isset($_POST['year']) ? intval($_POST['year']) : 0;
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $osd_days = isset($_POST['osd_days']) ? intval($_POST['osd_days']) : 0;
    $training_days = isset($_POST['training_days']) ? intval($_POST['training_days']) : 0;
    $leave_days = isset($_POST['leave_days']) ? intval($_POST['leave_days']) : 0;
    $atid = uniqid('atid_');  // Generates a unique id like 'atid_614a1aafc7ff1'

    // Insert the new attendance record with the unique atid
    // $stmt = $pdo->prepare("INSERT INTO attendance_details (atid, pbno, name, year, month, osd, training, leave) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    // $stmt->execute([$atid, $pbno, $name, $year, $month, $osd, $training, $leave]);
    // Validate inputs
    if ($year < 2000 || $year > 2024 || empty($month) || $osd_days < 0 || $training_days < 0 || $leave_days < 0) {
        $error = 'Please provide valid input values.';
    } else {
        // Prepare SQL statement to insert attendance data
        $stmt = $conn->prepare("INSERT INTO `emp`.`attendance_data` (atid,PBno, year, month, osdDate, trainingDate, leaves) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisiii", $atid, $pbno, $year, $month, $osd_days, $training_days, $leave_days);

        if ($stmt->execute()) {
            $success = 'Attendance data saved successfully!';
        } else {
            $error = 'Error saving attendance data: ' . $stmt->error;
        }
        
        // Close connections
        $stmt->close();
        $conn->close();
    }

}


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
$stmt = $pdo->query("SELECT * FROM `emp`.`attendance_data` WHERE PBno = $pbno");
//"SELECT u.name, u.PBno, a.year, a.month, a.osdDate, a.trainingDate, a.leaves FROM `emp`.`user` u JOIN `emp`.`attendance_details` a ON u.pbno = a.pbno;");
$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['delete'])) {
    $atid = $_GET['delete'];
    
    // Delete the record
    $stmt = $pdo->prepare("DELETE FROM `emp`.`attendance_data` WHERE atid = ?");
    $stmt->execute([$atid]);
    if ($stmt->execute()) {
    // Redirect back to dashboard
    header("Location: attendance_dashboard.php?dsuccess=Attendance data deleted successfully!");
    exit;
    }else{
        $derror = 'Error deleting attendance data';
    }
}
$dsuccess = '';
if (isset($_GET['dsuccess'])) {
    $dsuccess = htmlspecialchars($_GET['dsuccess']);
}


$passchangesuccess = '';
if (isset($_GET['passchangesuccess'])) {
    $passchangesuccess = htmlspecialchars($_GET['passchangesuccess']);
}

$editdetailsucces = '';
if (isset($_GET['editdetailsucces'])) {
    $editdetailsucces = htmlspecialchars($_GET['editdetailsucces']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #6a11cb;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }
        .container {
            max-width: 55%;
            margin: 60px auto;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            
        }
        .form-group {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        /* .form-group label {
            margin-right: 15px;
            width: 100px;
            font-weight: bold;
            font-size: 16px;
        } */
        .form-group label {
            display: block; 
            margin-right: 15px;
            
            font-size: 16px;
            color: #555;
        }
        .form-group input,
        .form-group select {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-horizontal {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-horizontal .form-group {
            flex: 1;
            margin-right: 20px;
        }
        .form-horizontal .form-group:last-child {
            margin-right: 0;
        }
        .submit-btn {
            width: 40%;
            padding: 15px;
            background-color: #6a11cb;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            margin : auto;

            /* display: block;
            width: 100%;
            padding: 12px;
            background-color: #6a11cb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase; */
        }
        .submit-div{
            text-align: center;
            padding:auto;
        }
        .submit-btn:hover {
            background-color: #333;
        }

        .header a:hover{
            color: #37B7C3;
            text-decoration: underline;
        }
        .header a:hover:not(:first-child){
            color: #e74c3c;
        }
        .dashboard-header{
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        .dashboard-header p {
            font-size: 16px;
            color: #777;
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
        .btn {
            padding: 5px 10px;
            background-color: #3c8dbc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        table {
             max-width: 100%;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .btn.edit {
            /* background-color: #4CAF50; */
            width: 40%;
            
            background-color: #6a11cb;
             border: none;
            border-radius: 5px;
            color: #fff;
           
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            margin : auto; 
        }
        .btn.delete {
            background-color: #f44336;
        }
        .btn.edit:hover {
            background-color: #777;
        }.btn.delete:hover {
            background-color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Attendance Details</h2>
        <div>
            <a href="change_password.php">Change Password</a>
            <a class="logoutbutton" href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
    <div class="dashboard-header">
            <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
            <p>PB Number: <?php echo htmlspecialchars($pbno); ?></p>
        </div>
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($passchangesuccess): ?>
            <div class="message success"><?php echo $passchangesuccess; ?></div>
        <?php endif; ?>
        <?php if ($editdetailsucces): ?>
            <div class="message success"><?php echo $editdetailsucces; ?></div>
        <?php endif; ?>
        <?php if ($dsuccess): ?>
            <div class="message success"><?php echo $dsuccess; ?></div>
        <?php endif; ?>
        
        <form  method="POST" action="attendance_dashboard.php">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select id="year" name="year" required title="Select a Year in the list">
                    <option value="" disabled selected>Select Year</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="month">Month:</label>
                    <select id="month" name="month" required title="Select a Month in the list">
                    <option value="" disabled selected>Select Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                </div>
               
            </div>

            <div class="form-horizontal">
               
                <div class="form-group">
                    <label for="osd_days">OSD:</label>
                    <input type="number" id="osd_days" name="osd_days" min="0" max="31" required>
                </div>
                <div class="form-group">
                    <label for="training_days">Training:</label>
                    <input type="number" id="training_days" name="training_days" min="0" max="31" required>
                </div>
                <div class="form-group">
                    <label for="leaves_days">Leaves:</label>
                    <input type="number" id="leave_days" name="leave_days" min="0" max="31" required>
                </div>
            </div>
            <div class="submit-div">
                <button type="submit" class="submit-btn">Submit Attendance</button>
            </div>
            
        </form>
    </div>
    <div class="container">
        <h2>Attendance Details</h2>
        <table>
            <tr>
                <th>PB No.</th>
                <th>Name</th>
                <th>Year</th>
                <th>Month</th>
                <th>OSD Days</th>
                <th>Training Days</th>
                <th>Leave Days</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['PBno']) ?></td>
                    <td><?= htmlspecialchars($name) ?></td>
                    <td><?= htmlspecialchars($record['year']) ?></td>
                    <td><?= htmlspecialchars($record['month']) ?></td>
                    <td><?= htmlspecialchars($record['osdDate']) ?></td>
                    <td><?= htmlspecialchars($record['trainingDate']) ?></td>
                    <td><?= htmlspecialchars($record['leaves']) ?></td>
                    <td>
                        <a href="edit_attendance.php?pbno=<?= urlencode($pbno) ?>&atid=<?= urlencode($record['atid']) ?>&name=<?= urlencode($name) ?>" class="btn edit">Edit</a>
                    </td>
                    <td>
                <a href="attendance_dashboard.php?delete=<?= $record['atid'] ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
            </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script>
    
        // const yearSelect = document.getElementById('year');
        // const currentYear = new Date().getFullYear();

        // for (let year = 2000; year <= currentYear; year++) {
        //     const option = document.createElement('option');
        //     option.value = year;
        //     option.textContent = year;
        //     yearSelect.appendChild(option);
        // }



        const yearSelect = document.getElementById('year');
        const currentYear = new Date().getFullYear();
        for (let i = currentYear; i >= 2000; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            yearSelect.appendChild(option);
        }
    </script>

</body>
</html>