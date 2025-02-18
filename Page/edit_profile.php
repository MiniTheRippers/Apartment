<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลจากตาราง users
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "C:/xampp/htdocs/LabApartment/image/";
        $target_file = $target_dir .  basename($_FILES["profile_pic"]["name"]);
        $target_detail = basename($_FILES["profile_pic"]["name"]);
        
        // ตรวจสอบประเภทของไฟล์
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif']; // กำหนดประเภทไฟล์ที่รองรับ
        if (in_array($_FILES['profile_pic']['type'], $allowed_types)) {
            // ตรวจสอบขนาดของไฟล์ (สูงสุด 2MB)
            if ($_FILES['profile_pic']['size'] <= 2000000) {
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    // อัปเดตข้อมูลในฐานข้อมูลรวมถึงรูปภาพ
                    $sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', address='$address', profile_pic='http://localhost/labapartment/image/$target_detail' WHERE user_id=$user_id";
                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='profile.php';</script>";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์!";
                }
            } else {
                echo "ขออภัย ขนาดไฟล์เกิน 2MB!";
            }
        } else {
            echo "ขออภัย ประเภทไฟล์ไม่รองรับ!";
        }
    } else {
        // อัปเดตเฉพาะข้อมูล (ไม่รวมรูป)
        $sql = "UPDATE users SET full_name='$full_name', email='$email', phone='$phone', address='$address' WHERE user_id=$user_id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='profile.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">✏️ แก้ไขโปรไฟล์</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo $user['full_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">📞 เบอร์โทร</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">🏡 ที่อยู่</label>
                <input type="text" name="address" class="form-control" value="<?php echo $user['address']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รูปโปรไฟล์</label>
                <input type="file" name="profile_pic" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="profile.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</body>
</html>
