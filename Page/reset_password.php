<?php
session_start();
include '../DB/db_connect.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f7fc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center mb-4">🔑 รีเซ็ตรหัสผ่าน</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="reset_password.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" method="post">
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่านใหม่:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">รีเซ็ตรหัสผ่าน</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">🔙 กลับไปที่เข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>
