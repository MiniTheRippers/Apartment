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
if (isset($_POST['add_repair'])) {
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO repairs (room_id, description, request_date, status) VALUES ('$room_id', '$description', '$request_date', '$status')";
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
    $sql = "SELECT * FROM repairs WHERE room_id LIKE '%$search%' OR description LIKE '%$search%' OR status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM repairs";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_repair'])) {
    $repair_id = $_POST['repair_id'];
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "UPDATE repairs SET room_id='$room_id', description='$description', request_date='$request_date', status='$status' WHERE repair_id='$repair_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $repair_id = $_GET['delete'];
    $sql = "DELETE FROM repairs WHERE repair_id='$repair_id'";
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
    <title>จัดการการซ่อมแซม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการการซ่อมแซม</h2>
        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- Form for adding new repair -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID</label>
                <input type="text" class="form-control" id="room_id" name="room_id" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="request_date" class="form-label">Request Date</label>
                <input type="date" class="form-control" id="request_date" name="request_date" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="รอซ่อม">รอซ่อม</option>
                    <option value="กำลังซ่อม">กำลังซ่อม</option>
                    <option value="ซ่อมเสร็จแล้ว">ซ่อมเสร็จแล้ว</option>
                </select>
            </div>
            <button type="submit" name="add_repair" class="btn btn-primary">เพิ่มการซ่อมแซม</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_repairs.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาการซ่อมแซม..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- Display repairs -->
        <table class="table">
            <thead>
                <tr>
                    <th>Repair ID</th>
                    <th>Room ID</th>
                    <th>Description</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['repair_id']; ?></td>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['request_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit_repair.php?id=<?php echo $row['repair_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete=<?php echo $row['repair_id']; ?>" class="btn btn-danger">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">ไม่มีข้อมูลการซ่อมแซม</td>
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