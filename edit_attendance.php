<?php
session_start();

// Database connection
$host = 'localhost';
$dbname = `emp`.`attendance_data`;
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get PB No. and Name from the query string
$pbno = $_GET['pbno'] ?? null;
$atid = $_GET['atid'] ?? null;
$name = $_GET['name'] ?? null;
if ($pbno && $atid) {
    // Fetch attendance details for the given PB No.
    $stmt = $pdo->prepare("SELECT * FROM `emp`.`attendance_data` WHERE atid = ? && PBno = ?");
    $stmt->execute([$atid,$pbno]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update attendance details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $osd = $_POST['osd'];
    $training = $_POST['training'];
    $leave = $_POST['leave'];
    $pp = $pbno; 
    $aa = $atid;
    // Update the attendance record
    $stmt = $pdo->prepare("UPDATE `emp`.`attendance_data` SET osdDate = ?, trainingDate = ?, leaves = ? WHERE atid = ? AND PBno = ?");
    $stmt->execute([$osd, $training, $leave, $aa,$pp]);

    if ($stmt->execute()) {
        header("Location: attendance_dashboard.php?editdetailsucces=Attendance Details Edited successfully!!");
        exit;
    } else {
        $error = 'Error Editing attendance data: ';
    }
    // Redirect to avoid form resubmission
   
        //   $pbno&month=$months" . urlencode($months));
    
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body {
             font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            /* background-color: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 600px; */
            max-width: 60%;
            width: 100%;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            /* margin-bottom: 20px;
            font-size: 24px;
            text-align: center;*/
            margin-top:10px; 
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        p{
   
            text-align: center;     
            font-size: 16px;
            color: #777;
        
        }

        .form-group {
            display: flex;
         /* justify-content: space-evenly;  */
            align-items: center;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
          /* align-items:stretch; */
            /* margin-bottom: 20px;
            display: flex;
            align-items: center; */
        }
        .form-group label {
            display: block; 
            margin-right: 15px;
            
            font-size: 16px;
            color: #000;
        }
       .form-horizontal {
            display: flex;
            /* justify-content: center; */
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .form-horizontal .form-group {
            flex: 2;
            margin-right: 10px;
        }
        .form-horizontal .form-group:last-child {
            margin-right: 0;
        }
        .form-group input {
            padding: 10px;
            width: 38%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
      
        .btn {
            width: 40%;
            padding: 10px;
            background-color: #6a11cb;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            margin : auto;
        }
        .btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Attendance for PB No: <?= htmlspecialchars($pbno) ?></h2>

    <?php if ($record): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Month:</strong> <?= htmlspecialchars($record['month']) ?></p>
        <form action="edit_attendance.php?pbno=<?= urlencode($pbno) ?>&atid=<?= urlencode($atid) ?>" method="post"> 
            <div class="form-horizontal"> 
            <div class="form-group">
                <label for="osd">OSD Days:</label>
                <input type="number" id="osd" name="osd" value="<?= htmlspecialchars($record['osdDate']) ?>"min="0" max="31" required>
            </div>
            <div class="form-group">
                <label for="training">Training Days:</label>
                <input type="number" id="training" name="training" value="<?= htmlspecialchars($record['trainingDate']) ?>"min="0" max="31" required>
            </div>
            <div class="form-group">
                <label for="leave">Leave Days:</label>
                <input type="number" id="leave" name="leave" value="<?= htmlspecialchars($record['leaves']) ?>"min="0" max="31" required>
            </div>
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    <?php else: ?>
        <p>No attendance record found for the given PB No.</p>
    <?php endif; ?>
</div>

</body>
</html>
