<?php
session_start(); // เริ่ม session เพื่อดึงข้อมูลผู้ใช้
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาล็อกอินก่อนเช่าห้องพัก'); window.location='login.php';</script>";
    exit();
}

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $user_id = $_SESSION['user_id']; // ดึง user_id จาก session

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();

        if ($room['status'] == 'ว่าง') {
            // เปลี่ยนสถานะห้องเป็น 'มีคนเช่า' และบันทึก user_id
            $update_stmt = $conn->prepare("UPDATE rooms SET status = 'มีคนเช่า', user_id = ? WHERE room_id = ?");
            $update_stmt->bind_param("ii", $user_id, $room_id);

            if ($update_stmt->execute()) {
                echo "<script>alert('คุณได้เช่าห้องเรียบร้อยแล้ว'); window.location='Dashboard.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตสถานะห้อง'); window.location='Dashboard.php';</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('ห้องนี้ไม่ว่างแล้ว'); window.location='Dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบห้องที่เลือก'); window.location='Dashboard.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('ไม่พบห้องที่เลือก'); window.location='Dashboard.php';</script>";
}

$conn->close();
?>