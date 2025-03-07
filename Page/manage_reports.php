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
if (isset($_POST['add_report'])) {
    $user_id = $_POST['user_id'];
    $report_type = $_POST['report_type'];

    $sql = "INSERT INTO reports (user_id, report_type) VALUES ('$user_id', '$report_type')";
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
    $sql = "SELECT * FROM reports WHERE user_id LIKE '%$search%' OR report_type LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM reports";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_report'])) {
    $report_id = $_POST['report_id'];
    $user_id = $_POST['user_id'];
    $report_type = $_POST['report_type'];

    $sql = "UPDATE reports SET user_id='$user_id', report_type='$report_type' WHERE report_id='$report_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $report_id = $_GET['delete'];
    $sql = "DELETE FROM reports WHERE report_id='$report_id'";
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
    <title>จัดการรายงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการรายงาน</h2>
        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- Form for adding new report -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="text" class="form-control" id="user_id" name="user_id" required>
            </div>
            <div class="mb-3">
                <label for="report_type" class="form-label">Report Type</label>
                <input type="text" class="form-control" id="report_type" name="report_type" required>
            </div>
            <button type="submit" name="add_report" class="btn btn-primary">เพิ่มรายงาน</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_reports.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหารายงาน..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- Display reports -->
        <table class="table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>User ID</th>
                    <th>Report Type</th>
                    <th>Generated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['report_id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['report_type']; ?></td>
                            <td><?php echo $row['generated_at']; ?></td>
                            <td>
                                <a href="edit_report.php?id=<?php echo $row['report_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete=<?php echo $row['report_id']; ?>" class="btn btn-danger">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีข้อมูลรายงาน</td>
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