<?php
session_start();
include '../DB/db_connect.php';
// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// CRUD Operations

// Create
if (isset($_POST['add_employee'])) {
    $user_id = $_POST['user_id'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO employees (user_id, position, phone) VALUES ('$user_id', '$position', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ตรวจสอบการค้นหา
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Read
if (!empty($search)) {
    $sql = "SELECT * FROM employees WHERE user_id LIKE '%$search%' OR position LIKE '%$search%' OR phone LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM employees";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_employee'])) {
    $employee_id = $_POST['employee_id'];
    $user_id = $_POST['user_id'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];

    $sql = "UPDATE employees SET user_id='$user_id', position='$position', phone='$phone' WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $employee_id = $_GET['delete'];
    $sql = "DELETE FROM employees WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการพนักงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการพนักงาน</h2>
        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- Form for adding new employee -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="text" class="form-control" id="user_id" name="user_id" required>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" name="add_employee" class="btn btn-primary">เพิ่มพนักงาน</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_employees.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาพนักงาน..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- Display employees -->
        <table class="table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>User ID</th>
                    <th>Position</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['employee_id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['position']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $row['employee_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete=<?php echo $row['employee_id']; ?>" class="btn btn-danger">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีข้อมูลพนักงาน</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>