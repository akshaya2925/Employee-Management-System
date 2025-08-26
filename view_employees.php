<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'header.php';

// Fetch all employees with the 'admin' role
$admins = $conn->query("SELECT e.*, p.name as project_name FROM employees e LEFT JOIN projects p ON e.project_id = p.id WHERE e.role = 'admin' ORDER BY e.name");

// Fetch all employees with the 'employee' role
$employees = $conn->query("SELECT e.*, p.name as project_name FROM employees e LEFT JOIN projects p ON e.project_id = p.id WHERE e.role = 'employee' ORDER BY e.name");
?>
        <h2>User Management</h2>

        <div class="user-section">
            <h3>Admin Users</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Project</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $admins->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['project_name']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if (empty($admins->num_rows)): ?>
                    <tr><td colspan="3">No admin users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="user-section">
            <h3>Regular Employees</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Project</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $employees->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['project_name']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if (empty($employees->num_rows)): ?>
                    <tr><td colspan="3">No regular employees found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>