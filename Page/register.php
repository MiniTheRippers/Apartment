<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    
    // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password !== $confirm_password) {
        $error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
    } else {
        // เข้ารหัสรหัสผ่าน
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // ตรวจสอบว่ามี username ซ้ำหรือไม่
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "ชื่อผู้ใช้นี้ถูกใช้แล้ว";
        } else {
            // เพิ่มผู้ใช้ใหม่ในฐานข้อมูล
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, full_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password_hash, $email, $full_name);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "ลงทะเบียนสำเร็จ! กรุณาเข้าสู่ระบบ";
                header("Location: login.php");
                exit();
            } else {
                $error = "เกิดข้อผิดพลาด กรุณาลองอีกครั้ง";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <h2>ลงทะเบียน</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="post">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">อีเมล:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="full_name">ชื่อ-นามสกุล:</label>
            <input type="text" id="full_name" name="full_name" required>
            
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">ยืนยันรหัสผ่าน:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">ลงทะเบียน</button>
        </form>
        <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>
