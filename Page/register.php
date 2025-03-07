<?php
session_start();
include '../DB/db_connect.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
            width: 100%;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="mb-4">ลงทะเบียน</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">ชื่อ-นามสกุล:</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom">ลงทะเบียน</button>
        </form>
        <p class="mt-3">มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>