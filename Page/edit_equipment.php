<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $equipment_id = $_GET['id'];
    $sql = "SELECT * FROM room_equipment WHERE equipment_id='$equipment_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update_equipment'])) {
    $equipment_id = $_POST['equipment_id'];
    $room_id = $_POST['room_id'];
    $equipment_name = $_POST['equipment_name'];
    $status = $_POST['status'];

    $sql = "UPDATE room_equipment SET room_id='$room_id', equipment_name='$equipment_name', status='$status' WHERE equipment_id='$equipment_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_room_equipment.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขอุปกรณ์ห้อง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ระบบจัดการห้องพัก</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="admin_dashboard.php">แดชบอร์ด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_rooms.php">จัดการห้อง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_room_equipment.php">จัดการอุปกรณ์ห้อง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">แก้ไขอุปกรณ์ห้อง</h2>
        <form method="post">
            <input type="hidden" name="equipment_id" value="<?php echo $row['equipment_id']; ?>">
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID</label>
                <input type="text" class="form-control" id="room_id" name="room_id" value="<?php echo $row['room_id']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="equipment_name" class="form-label">Equipment Name</label>
                <input type="text" class="form-control" id="equipment_name" name="equipment_name" value="<?php echo $row['equipment_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ใช้งานได้" <?php echo ($row['status'] == 'ใช้งานได้') ? 'selected' : ''; ?>>ใช้งานได้</option>
                    <option value="เสียหาย" <?php echo ($row['status'] == 'เสียหาย') ? 'selected' : ''; ?>>เสียหาย</option>
                    <option value="ต้องเปลี่ยน" <?php echo ($row['status'] == 'ต้องเปลี่ยน') ? 'selected' : ''; ?>>ต้องเปลี่ยน</option>
                </select>
            </div>
            <button type="submit" name="update_equipment" class="btn btn-primary">อัปเดตอุปกรณ์</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>