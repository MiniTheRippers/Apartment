<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $repair_id = $_GET['id'];
    $sql = "SELECT * FROM repairs WHERE repair_id='$repair_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update_repair'])) {
    $repair_id = $_POST['repair_id'];
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "UPDATE repairs SET room_id='$room_id', description='$description', request_date='$request_date', status='$status' WHERE repair_id='$repair_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_repairs.php");
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
    <title>แก้ไขการซ่อมแซม</title>
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
                        <a class="nav-link" href="manage_repairs.php">จัดการการซ่อมแซม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">แก้ไขการซ่อมแซม</h2>
        <form method="post">
            <input type="hidden" name="repair_id" value="<?php echo $row['repair_id']; ?>">
            <div class="mb-3">
                <label for="room_id" class="form-label">Room ID</label>
                <input type="text" class="form-control" id="room_id" name="room_id" value="<?php echo $row['room_id']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $row['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="request_date" class="form-label">Request Date</label>
                <input type="date" class="form-control" id="request_date" name="request_date" value="<?php echo $row['request_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="รอซ่อม" <?php echo ($row['status'] == 'รอซ่อม') ? 'selected' : ''; ?>>รอซ่อม</option>
                    <option value="กำลังซ่อม" <?php echo ($row['status'] == 'กำลังซ่อม') ? 'selected' : ''; ?>>กำลังซ่อม</option>
                    <option value="ซ่อมเสร็จแล้ว" <?php echo ($row['status'] == 'ซ่อมเสร็จแล้ว') ? 'selected' : ''; ?>>ซ่อมเสร็จแล้ว</option>
                </select>
            </div>
            <button type="submit" name="update_repair" class="btn btn-primary">อัปเดตการซ่อมแซม</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>