<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $sql = "SELECT * FROM reports WHERE report_id='$report_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update_report'])) {
    $report_id = $_POST['report_id'];
    $user_id = $_POST['user_id'];
    $report_type = $_POST['report_type'];

    $sql = "UPDATE reports SET user_id='$user_id', report_type='$report_type' WHERE report_id='$report_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_reports.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรายงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link active" aria-current="page" href="admin_dashboard.php">แดชบอร์ด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_rooms.php">จัดการห้อง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_reports.php">จัดการรายงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">แก้ไขรายงาน</h2>
        <form method="post">
            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $row['user_id']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="report_type" class="form-label">Report Type</label>
                <input type="text" class="form-control" id="report_type" name="report_type" value="<?php echo $row['report_type']; ?>" required>
            </div>
            <button type="submit" name="update_report" class="btn btn-primary">อัปเดตรายงาน</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>