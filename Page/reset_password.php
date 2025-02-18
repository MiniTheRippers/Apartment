<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // ตรวจสอบว่า token ถูกต้องหรือไม่
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $error = "ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุ";
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            
            // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
            if ($password !== $confirm_password) {
                $error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
            } else {
                // เข้ารหัสรหัสผ่าน
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // อัปเดตรหัสผ่านในฐานข้อมูล
                $stmt = $conn->prepare("UPDATE users SET password_hash = ?, reset_token = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $password_hash, $token);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "รหัสผ่านของคุณได้รับการรีเซ็ตเรียบร้อยแล้ว";
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง";
                }
            }
        }
    }
    $stmt->close();
} else {
    $error = "ไม่มี token สำหรับรีเซ็ตรหัสผ่าน";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="reset-password-container">
        <h2>รีเซ็ตรหัสผ่าน</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="reset_password.php?token=<?php echo $_GET['token']; ?>" method="post">
            <label for="password">รหัสผ่านใหม่:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">ยืนยันรหัสผ่านใหม่:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">รีเซ็ตรหัสผ่าน</button>
        </form>
        <p>กลับไปที่ <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>
