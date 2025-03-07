<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xamppNew\htdocs\LabApartment\vendor\autoload.php'; // โหลด PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // เชื่อมต่อกับฐานข้อมูล
    include '../DB/db_connect.php';

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $stmt->close();

        // อัปเดต token
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // ส่งอีเมล
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'realminifierys@gmail.com';
            $mail->Password = 'jzzw zcik shty bfre';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Lab Apartment Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'การกู้คืนรหัสผ่าน';
            $mail->Body = 'คลิกที่ลิงก์นี้เพื่อรีเซ็ตรหัสผ่าน: 
                          <a href="localhost/LabApartment/page/reset_password.php?token=' . $token . '">รีเซ็ตรหัสผ่าน</a>';

            $mail->send();
            $message = '<div class="alert alert-success text-center">ลิงก์สำหรับการรีเซ็ตรหัสผ่านถูกส่งไปยังอีเมลของคุณแล้ว!</div>';
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger text-center">ไม่สามารถส่งอีเมลได้. ข้อผิดพลาด: ' . $mail->ErrorInfo . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning text-center">ไม่พบอีเมลในระบบ</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 100px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2 class="mb-4">ลืมรหัสผ่าน</h2>
        <?php if(isset($message)) echo $message; ?>
        <form action="forgot_password.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
        </form>
    </div>
</body>
</html>
