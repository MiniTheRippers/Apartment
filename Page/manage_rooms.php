<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ฟังก์ชันเพิ่มห้อง
if (isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type']; // ค่าที่ส่งมาจะต้องเป็น 'ห้องเดี่ยว', 'ห้องคู่', หรือ 'ห้องแฟมิลี่'
    $rent_price = $_POST['rent_price'];
    $user_id = $_SESSION['user_id']; // หรือรหัสผู้ใช้ที่เหมาะสม
    $status = 'ว่าง'; // ค่าเริ่มต้น

    // ตรวจสอบว่าหมายเลขห้องมีอยู่ในฐานข้อมูลหรือไม่
    $stmt_check = $conn->prepare("SELECT * FROM rooms WHERE room_number = ?");
    $stmt_check->bind_param("s", $room_number);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // หมายเลขห้องซ้ำ
        $error_message = "หมายเลขห้องนี้มีอยู่ในระบบแล้ว กรุณาใช้หมายเลขห้องอื่น";
    } else {
        // เพิ่มห้องใหม่
        $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type, rent_price, user_id, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $room_number, $room_type, $rent_price, $user_id, $status);
        $stmt->execute();
        $stmt->close();
    }
    $stmt_check->close();
}

// ฟังก์ชันลบห้อง
if (isset($_GET['delete_room'])) {
    $room_id = $_GET['delete_room'];

    $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $stmt->close();
}

// ตรวจสอบการค้นหา
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ดึงข้อมูลห้องทั้งหมด
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_number LIKE ? OR room_type LIKE ? OR status LIKE ?");
    $search_term = "%$search%";
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
} else {
    $stmt = $conn->prepare("SELECT * FROM rooms");
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้อง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการห้อง</h2>

        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- แสดงข้อความผิดพลาด -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- ฟอร์มเพิ่มห้อง -->
        <form action="manage_rooms.php" method="post" class="mb-4">
            <div class="mb-3">
                <label for="room_number" class="form-label">หมายเลขห้อง</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required>
            </div>
            <div class="mb-3">
                <label for="room_type" class="form-label">ประเภทห้อง</label>
                <select class="form-select" id="room_type" name="room_type" required>
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="rent_price" class="form-label">ราคาค่าเช่า</label>
                <input type="number" class="form-control" id="rent_price" name="rent_price" step="0.01" required>
            </div>
            <button type="submit" name="add_room" class="btn btn-primary">เพิ่มห้อง</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_rooms.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาห้องพัก..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- ตารางแสดงข้อมูลห้อง -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ประเภทห้อง</th>
                    <th>ราคาค่าเช่า</th>
                    <th>สถานะ</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['rent_price']); ?> บาท</td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="edit_room.php?room_id=<?php echo $row['room_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete_room=<?php echo $row['room_id']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบห้องนี้?');">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">ไม่มีข้อมูลห้องพัก</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>