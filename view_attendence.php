<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'header.php';

$attendance_data = $conn->query("SELECT e.name, a.attendance_date, a.status FROM attendance a JOIN employees e ON a.employee_id = e.id ORDER BY a.attendance_date DESC, e.name ASC");
?>
        <h2>Employee Attendance Records</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $attendance_data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['attendance_date']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if (empty($attendance_data->num_rows)): ?>
                <tr><td colspan="3">No attendance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>