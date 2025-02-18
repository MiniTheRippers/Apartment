<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลจากตาราง users
$sql = "SELECT full_name, email, role, phone, address, profile_pic FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
    <a href="Dashboard.php" class="btn btn-primary profile-btn">หน้าเเรก</a><a href="Dashboard.php" class="btn btn-primary profile-btn">หน้าเเรก</a>
        <h2 class="text-center">📌 ข้อมูลโปรไฟล์</h2>
        <div class="card mx-auto" style="width: 400px;">
            <img src="<?php echo $user['profile_pic'] ?? 'image/default_profile.jpg'; ?>" class="card-img-top" alt="โปรไฟล์">
            <div class="card-body text-center">
                <h5 class="card-title"><?php echo $user['full_name']; ?></h5>
                <p class="card-text">📧 <?php echo $user['email']; ?></p>
                <p class="card-text">🛠 บทบาท: <strong><?php echo $user['role']; ?></strong></p>

                <p class="card-text">📞 เบอร์โทร: <?php echo $user['phone']; ?></p>
                <p class="card-text">🏡 ที่อยู่: <?php echo $user['address']; ?></p>

                <a href="edit_profile.php" class="btn btn-warning">แก้ไขโปรไฟล์</a>
            </div>
        </div>
    </div>
</body>
</html>
