<?php
session_start();
include '../DB/db_connect.php';

// Regenerate session ID to prevent session fixation
if (!isset($_SESSION['user_id'])) {
    session_regenerate_id(true);
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // or another appropriate page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash, $role);
        $stmt->fetch();
        
        if (password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role == 'tenant') {
                header("Location: dashboard.php");
            } else if ($role == 'admin' || $role == 'employee') {
                header("Location: admin_dashboard.php");
            }
            exit();
        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"; // Incorrect username or password
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"; // Incorrect username or password
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Login Container */
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Title */
        .login-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        /* Error message */
        .error {
            color: red;
            margin-bottom: 10px;
        }

        /* Form elements */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        /* Submit Button */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Links */
        p {
            margin-top: 15px;
            font-size: 14px;
        }

        p a {
            color: #4CAF50;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="login.php" method="post">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p>ยังไม่มีบัญชี? <a href="register.php">ลงทะเบียน</a></p>
        
        <!-- ลิงก์ไปหน้าลืมรหัสผ่าน -->
        <p><a href="forgot_password.php">ลืมรหัสผ่าน?</a></p>
    </div>
</body>
</html>
