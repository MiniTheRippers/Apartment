<?php
session_start(); // เริ่ม session เพื่อดึงข้อมูลผู้ใช้
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

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

// ฟังก์ชันเลือกบริการเสริม
if (isset($_POST['add_service'])) {
    $service_id = $_POST['service_id'];
    $room_id = $_POST['room_id'];

    // ตรวจสอบว่าบริการเสริมนี้ยังไม่ได้ถูกเลือก
    $stmt_check = $conn->prepare("SELECT * FROM tenant_services WHERE tenant_id = ? AND service_id = ?");
    $stmt_check->bind_param("ii", $user_id, $service_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        // เพิ่มบริการเสริม
        $stmt_add = $conn->prepare("INSERT INTO tenant_services (tenant_id, service_id) VALUES (?, ?)");
        $stmt_add->bind_param("ii", $user_id, $service_id);
        $stmt_add->execute();
        $stmt_add->close();
    }
    $stmt_check->close();
}

// ฟังก์ชันลบบริการเสริม
if (isset($_GET['delete_service'])) {
    $service_id = $_GET['delete_service'];

    // ลบบริการเสริม
    $stmt_delete = $conn->prepare("DELETE FROM tenant_services WHERE tenant_id = ? AND service_id = ?");
    $stmt_delete->bind_param("ii", $user_id, $service_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ห้องพัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($user)) : ?>
            <div class="profile-info mb-3">
                <h4>โปรไฟล์ผู้ใช้</h4>
                <p>ชื่อ: <?php echo htmlspecialchars($user['username']); ?></p>
                <p>อีเมล: <?php echo htmlspecialchars($user['email']); ?></p>
                <a href="profile.php" class="btn btn-primary profile-btn">แก้ไขโปรไฟล์</a>
                <a href="login.php" class="btn btn-primary profile-btn">ล็อกเอ้า</a>
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
                    $rentButton = ($row['status'] == 'ว่าง') ? "<a href='rent_room.php?room_id={$row['room_id']}' class='btn btn-success' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการเช่าห้องนี้?\");'>เช่าห้อง</a>" : "";

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

                
                }
            } else {
                echo "<p class='text-center'>ไม่มีข้อมูลห้องพัก</p>";
            }
            $stmt->close();
            ?>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
