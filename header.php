<?php
// You can add logic here if needed, like checking user roles
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <a href="admin_dashboard.php"><i class="fas fa-home icon"></i> Home</a>
        <a href="add_employee.php"><i class="fas fa-user-plus icon"></i> Add Employee</a>
        <a href="view_employees.php"><i class="fas fa-users icon"></i> All Employees</a>
        <a href="view_leave_requests.php"><i class="fas fa-calendar-times icon"></i> Leave Requests</a>
        <a href="view_attendance.php"><i class="fas fa-calendar-check icon"></i> Attendance</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt icon"></i> Logout</a>
    </div>
    <div class="content">
        <div class="container">