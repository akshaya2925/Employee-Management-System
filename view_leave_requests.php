<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'header.php';

// Logic for managing leave requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve_leave']) || isset($_POST['reject_leave']))) {
    $request_id = $_POST['request_id'];
    $status = isset($_POST['approve_leave']) ? 'approved' : 'rejected';

    $sql = "UPDATE leave_requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
    header("Location: view_leave_requests.php");
    exit();
}

$leave_requests = $conn->query("SELECT lr.*, e.name as employee_name FROM leave_requests lr JOIN employees e ON lr.employee_id = e.id WHERE lr.status = 'pending' ORDER BY lr.start_date DESC");
?>
        <h2>Pending Leave Requests</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $leave_requests->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['employee_name']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?php echo $row['reason']; ?></td>
                    <td class="action-buttons">
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="approve_leave" class="approve-btn">Approve</button>
                        </form>
                        <form method="post" action="" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="reject_leave" class="reject-btn">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (empty($leave_requests->num_rows)): ?>
                <tr><td colspan="5">No pending leave requests.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>