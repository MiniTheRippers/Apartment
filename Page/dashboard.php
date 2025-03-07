<?php
include '../DB/db_connect.php';

session_start(); // เริ่ม session เพื่อดึงข้อมูลผู้ใช้


// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลโปรไฟล์ผู้ใช้
$user_id = $_SESSION['user_id'];
$stmt_user = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ห้องพัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .navbar {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            text-align: center;
        }
        .profile-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ระบบจัดการห้องพัก</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">โปรไฟล์</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($user)) : ?>
            <div class="profile-info mb-3">
                <h4>โปรไฟล์ผู้ใช้</h4>
                <p>ชื่อ: <?php echo htmlspecialchars($user['username']); ?></p>
                <p>อีเมล: <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        <?php endif; ?>

        <h2 class="text-center">📌 รายการห้องพัก</h2>
        <div class="row">
            <?php
            $stmt = $conn->prepare("SELECT * FROM rooms");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusColor = ($row['status'] == 'ว่าง') ? 'bg-success' : 'bg-danger';
                    $rentButton = ($row['status'] == 'ว่าง') ? "<a href='#' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#confirmModal{$row['room_id']}'>เช่าห้อง</a>" : "";

                    $editButton = "";
                    $reviewButton = "";
                    if ($row['status'] == 'มีคนเช่า' && $row['user_id'] == $user_id) {
                        $editButton = "<a href='edit_room.php?room_id={$row['room_id']}' class='btn btn-warning'>แก้ไขห้อง</a>";
                        $reviewButton = "<a href='add_review.php?room_id={$row['room_id']}' class='btn btn-info'>เขียนรีวิว</a>";
                    }

                    echo "
                    <div class='col-md-4'>
                        <div class='card mb-3'>
                            <img src='{$row['room_image']}' class='card-img-top' alt='ห้อง {$row['room_number']}'>
                            <div class='card-body'>
                                <h5 class='card-title'>ห้อง {$row['room_number']}</h5>
                                <p class='card-text'>ประเภท: {$row['room_type']}</p>
                                <p class='card-text'>ราคา: {$row['rent_price']} บาท</p>
                                <span class='badge {$statusColor}'>{$row['status']}</span><br> 
                                {$rentButton}
                                {$editButton}
                                {$reviewButton}
                            </div>
                        </div>
                    </div>";

                    // Modal สำหรับการยืนยันการเช่า
                    if ($row['status'] == 'ว่าง') {
                        echo "
                        <div class='modal fade' id='confirmModal{$row['room_id']}' tabindex='-1' role='dialog' aria-labelledby='confirmModalLabel{$row['room_id']}' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='confirmModalLabel{$row['room_id']}'>ยืนยันการเช่าห้อง</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        คุณแน่ใจหรือไม่ว่าต้องการเช่าห้องนี้?
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ยกเลิก</button>
                                        <a href='rent_room.php?room_id={$row['room_id']}' class='btn btn-success'>ยืนยัน</a>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                }
            } else {
                echo "<p class='text-center'>ไม่มีข้อมูลห้องพัก</p>";
            }
            $stmt->close();
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 ระบบจัดการห้องพัก. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
