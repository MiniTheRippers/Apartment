<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $user_id = $_SESSION['user_id'];

    // ตรวจสอบว่าผู้ใช้เป็นผู้เช่าห้องนี้หรือไม่
    $sql = "SELECT * FROM rooms WHERE room_id = $room_id AND user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "<p>คุณไม่มีสิทธิ์ยกเลิกการเช่าห้องนี้</p>";
        exit();
    }
} else {
    echo "<p>ไม่พบห้องที่เลือก</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ยกเลิกการเช่าห้องพัก
    $update_sql = "UPDATE rooms SET status = 'ว่าง', user_id = NULL WHERE room_id = $room_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('ยกเลิกการเช่าห้องเรียบร้อยแล้ว'); window.location='Dashboard.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการยกเลิกการเช่าห้อง: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยกเลิกการเช่าห้องพัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>ยกเลิกการเช่าห้องพัก</h2>
        <p>คุณกำลังจะยกเลิกการเช่าห้องพักหมายเลข: <strong><?php echo $room['room_number']; ?></strong></p>
        <p>ประเภทห้อง: <?php echo $room['room_type']; ?></p>
        <p>ราคาเช่า: <?php echo $room['rent_price']; ?> บาท</p>

        <form method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการเช่าห้องพักนี้?');">
            <button type="submit" class="btn btn-danger">ยกเลิกการเช่า</button>
            <a href="Dashboard.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</body>
</html>