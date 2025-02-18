<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHMailer\Exception;

require 'C:\xampp\htdocs\LabApartment\vendor\autoload.php'; // โหลด PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // เชื่อมต่อกับฐานข้อมูลเพื่อตรวจสอบอีเมล
    include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php'; // เชื่อมต่อกับฐานข้อมูล

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // สร้าง token สำหรับการกู้คืนรหัสผ่าน
        $token = bin2hex(random_bytes(32)); // สร้าง token
        $stmt->close();

        // อัปเดต token ในฐานข้อมูล
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // ส่งอีเมลสำหรับกู้คืนรหัสผ่าน
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'realminifierys@gmail.com'; // อีเมลของคุณ
            $mail->Password = 'jzzw zcik shty bfre'; // รหัสผ่านของแอป (หากเปิดการยืนยัน 2 ขั้นตอน)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ใช้การเชื่อมต่อแบบ STARTTLS
            $mail->Port = 587; // พอร์ตสำหรับ STARTTLS

            $mail->setFrom('your_email@gmail.com', 'Mailer');
            $mail->addAddress($email); // อีเมลที่รับคำขอรีเซ็ตรหัส

            $mail->isHTML(true);
            $mail->Subject = 'การกู้คืนรหัสผ่าน';
            $mail->Body    = 'คลิกที่ลิงก์นี้เพื่อรีเซ็ตรหัสผ่าน: <a href="http://your_website.com/reset_password.php?token=' . $token . '">รีเซ็ตรหัสผ่าน</a>';

            $mail->send();
            echo 'ลิงก์สำหรับการรีเซ็ตรหัสผ่านถูกส่งไปยังอีเมลของคุณแล้ว!';
        } catch (Exception $e) {
            echo "ไม่สามารถส่งอีเมลได้. ข้อผิดพลาด: {$mail->ErrorInfo}";
        }
    } else {
        echo 'ไม่พบอีเมลในระบบ';
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
</head>
<body>
    <h2>ลืมรหัสผ่าน</h2>
    <form action="forgot_password.php" method="post">
        <label for="email">อีเมล:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
    </form>
</body>
</html>
