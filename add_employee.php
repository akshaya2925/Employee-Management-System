<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
include 'header.php';

// Logic for adding employee
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $project_id = empty($_POST['project_id']) ? NULL : $_POST['project_id'];

    $sql = "INSERT INTO employees (name, email, password, role, project_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $password, $role, $project_id);
    $stmt->execute();
    header("Location: view_employees.php");
    exit();
}

$projects = $conn->query("SELECT * FROM projects");
?>
        <h2>Add New Employee</h2>
        <form method="post" action="">
            Name: <input type="text" name="name" required><br><br>
            Email: <input type="email" name="email" required><br><br>
            Password: <input type="password" name="password" required><br><br>
            Role:
            <select name="role">
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select><br><br>
            Project:
            <select name="project_id">
                <option value="">None</option>
                <?php while($row = $projects->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <button type="submit">Add Employee</button>
        </form>
    </div>
</body>
</html>