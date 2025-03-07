<?php
session_start(); // เริ่ม session
session_unset(); // ลบตัวแปร session ทั้งหมด
session_destroy(); // ทำลาย session

// Redirect ไปยังหน้า login
header("Location: login.php");
exit();
?>