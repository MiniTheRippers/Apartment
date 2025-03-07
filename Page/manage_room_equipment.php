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
if (isset($_POST['add_equipment'])) {
    $room_id = $_POST['room_id'];
    $equipment_name = $_POST['equipment_name'];
    $status = $_POST['status'];

    $sql = "INSERT INTO room_equipment (room_id, equipment_name, status) VALUES ('$room_id', '$equipment_name', '$status')";
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
    $sql = "SELECT * FROM room_equipment WHERE room_id LIKE '%$search%' OR equipment_name LIKE '%$search%' OR status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM room_equipment";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_equipment'])) {
    $equipment_id = $_POST['equipment_id'];
    $room_id = $_POST['room_id'];
    $equipment_name = $_POST['equipment_name'];
    $status = $_POST['status'];

    $sql = "UPDATE room_equipment SET room_id='$room_id', equipment_name='$equipment_name', status='$status' WHERE equipment_id='$equipment_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $equipment_id = $_GET['delete'];
    $sql = "DELETE FROM room_equipment WHERE equipment_id='$equipment_id'";
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
    <title>จัดการอุปกรณ์ห้อง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการอุปกรณ์ห้อง</h2>
        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- Form for adding new equipment -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID</label>
                <input type="text" class="form-control" id="room_id" name="room_id" required>
            </div>
            <div class="mb-3">
                <label for="equipment_name" class="form-label">Equipment Name</label>
                <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ใช้งานได้">ใช้งานได้</option>
                    <option value="เสียหาย">เสียหาย</option>
                    <option value="ต้องเปลี่ยน">ต้องเปลี่ยน</option>
                </select>
            </div>
            <button type="submit" name="add_equipment" class="btn btn-primary">เพิ่มอุปกรณ์</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_equipment.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาอุปกรณ์..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- Display equipment -->
        <table class="table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Room ID</th>
                    <th>Equipment Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['equipment_id']; ?></td>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['equipment_name']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit_equipment.php?id=<?php echo $row['equipment_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete=<?php echo $row['equipment_id']; ?>" class="btn btn-danger">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีข้อมูลอุปกรณ์</td>
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