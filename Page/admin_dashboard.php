<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ตรวจสอบการค้นหา
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ดึงข้อมูลสถิติ
$stmt_total_rooms = $conn->prepare("SELECT COUNT(*) AS total_rooms FROM rooms");
$stmt_total_rooms->execute();
$result_total_rooms = $stmt_total_rooms->get_result();
$total_rooms = $result_total_rooms->fetch_assoc()['total_rooms'];

$stmt_total_rented = $conn->prepare("SELECT COUNT(*) AS total_rented FROM rooms WHERE status = 'มีคนเช่า'");
$stmt_total_rented->execute();
$result_total_rented = $stmt_total_rented->get_result();
$total_rented = $result_total_rented->fetch_assoc()['total_rented'];

$stmt_total_users = $conn->prepare("SELECT COUNT(*) AS total_users FROM users");
$stmt_total_users->execute();
$result_total_users = $stmt_total_users->get_result();
$total_users = $result_total_users->fetch_assoc()['total_users'];

$stmt_total_rooms->close();
$stmt_total_rented->close();
$stmt_total_users->close();

// ดึงข้อมูลห้องพักตามคำค้นหา
if (!empty($search)) {
    $stmt_rooms = $conn->prepare("SELECT * FROM rooms WHERE room_number LIKE ? OR room_type LIKE ? OR status LIKE ?");
    $search_term = "%$search%";
    $stmt_rooms->bind_param("sss", $search_term, $search_term, $search_term);
} else {
    $stmt_rooms = $conn->prepare("SELECT * FROM rooms");
}
$stmt_rooms->execute();
$result_rooms = $stmt_rooms->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดผู้ดูแลระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
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
                        <a class="nav-link " aria-current="page" href="admin_dashboard.php">แดชบอร์ด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_rooms.php">จัดการห้อง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_room_equipment.php">จัดการอุปกรณ์ห้อง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_repairs.php">จัดการการซ่อมแซม</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_payments.php">จัดการการชำระเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_employees.php">จัดการพนักงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="manage_reports.php">จัดการรายงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">📊 สถิติการเช่าห้อง</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนห้องทั้งหมด</h5>
                        <p class="card-text"><?php echo $total_rooms; ?> ห้อง</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนห้องที่ถูกเช่า</h5>
                        <p class="card-text"><?php echo $total_rented; ?> ห้อง</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">จำนวนผู้ใช้ทั้งหมด</h5>
                        <p class="card-text"><?php echo $total_users; ?> คน</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ฟอร์มค้นหา -->
        <form action="admin_dashboard.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาห้องพัก..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- แสดงผลการค้นหาห้องพัก -->
        <h2 class="text-center">📌 รายการห้องพัก</h2>
        <div class="row">
            <?php if ($result_rooms->num_rows > 0): ?>
                <?php while ($row = $result_rooms->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">ห้อง <?php echo $row['room_number']; ?></h5>
                                <p class="card-text">ประเภท: <?php echo $row['room_type']; ?></p>
                                <p class="card-text">สถานะ: <?php echo $row['status']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">ไม่มีข้อมูลห้องพัก</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$stmt_rooms->close();
$conn->close();
?>