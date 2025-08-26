<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$attendance_message = ''; // Initialize attendance message

// Logic for submitting leave request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO leave_requests (employee_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $start_date, $end_date, $reason);
    $stmt->execute();
    header("Location: employee_dashboard.php");
    exit();
}

// Logic for marking attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_attendance'])) {
    $today = date("Y-m-d");
    
    // Check if attendance has already been marked for today
    $sql_check = "SELECT id FROM attendance WHERE employee_id = ? AND attendance_date = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $user_id, $today);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        $sql_insert = "INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, 'present')";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("is", $user_id, $today);
        if ($stmt_insert->execute()) {
            $attendance_message = "Attendance marked as Present for today!";
        } else {
            $attendance_message = "Error marking attendance: " . $conn->error;
        }
    } else {
        $attendance_message = "You have already marked your attendance for today.";
    }
}

// Get user's leave requests
$leave_history = $conn->prepare("SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY start_date DESC");
$leave_history->bind_param("i", $user_id);
$leave_history->execute();
$result_leave = $leave_history->get_result();

$leave_requests = [];
while ($row = $result_leave->fetch_assoc()) {
    $leave_requests[] = $row;
}

// Get user's attendance history
$attendance_history_query = $conn->prepare("SELECT attendance_date, status FROM attendance WHERE employee_id = ? ORDER BY attendance_date DESC");
$attendance_history_query->bind_param("i", $user_id);
$attendance_history_query->execute();
$result_attendance = $attendance_history_query->get_result();

$attendance_records = [];
while ($row = $result_attendance->fetch_assoc()) {
    $attendance_records[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Employee Dashboard</h2>
        <a href="logout.php">Logout</a>

        <h3>Mark Today's Attendance</h3>
        <?php if (!empty($attendance_message)) echo "<p style='color:green;'>$attendance_message</p>"; ?>
        <form method="post" action="">
            <button type="submit" name="mark_attendance">Mark Present</button>
        </form>
        
        <hr>

        <h3>Submit Leave Request</h3>
        <form method="post" action="">
            Start Date: <input type="date" name="start_date" required><br><br>
            End Date: <input type="date" name="end_date" required><br><br>
            Reason: <br><textarea name="reason" rows="4" cols="50" required></textarea><br><br>
            <button type="submit" name="submit_leave">Submit Request</button>
        </form>
        
        <hr>
        
        <h3>My Leave Requests</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $request): ?>
                <tr>
                    <td><?php echo $request['start_date']; ?></td>
                    <td><?php echo $request['end_date']; ?></td>
                    <td><?php echo $request['reason']; ?></td>
                    <td><?php echo ucfirst($request['status']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($leave_requests)): ?>
                <tr><td colspan="4">No leave requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>

        <h3>My Attendance History</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance_records as $record): ?>
                <tr>
                    <td><?php echo $record['attendance_date']; ?></td>
                    <td><?php echo ucfirst($record['status']); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($attendance_records)): ?>
                <tr><td colspan="2">No attendance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>