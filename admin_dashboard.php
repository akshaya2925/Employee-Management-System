<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'header.php'; // Includes the new header and navigation

// Fetch some data for a quick overview on the homepage
$total_employees = $conn->query("SELECT COUNT(*) FROM employees")->fetch_assoc()['COUNT(*)'];
$pending_requests = $conn->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'pending'")->fetch_assoc()['COUNT(*)'];
?>

        <h2>Welcome, Admin!</h2>
        <p>This is your dashboard. Use the navigation menu on the left to manage employees, projects, and requests.</p>

        <div style="display: flex; justify-content: space-around; margin-top: 30px;">
            <div style="text-align: center; padding: 20px; background: #e0f7e9; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3>Total Employees</h3>
                <p style="font-size: 48px; color: #28a745;"><?php echo $total_employees; ?></p>
            </div>
            <div style="text-align: center; padding: 20px; background: #fff3e0; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3>Pending Leave Requests</h3>
                <p style="font-size: 48px; color: #ffc107;"><?php echo $pending_requests; ?></p>
            </div>
        </div>
    </div>
</body>
</html>