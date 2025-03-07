<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE employee_id='$employee_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update_employee'])) {
    $employee_id = $_POST['employee_id'];
    $user_id = $_POST['user_id'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];

    $sql = "UPDATE employees SET user_id='$user_id', position='$position', phone='$phone' WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_employees.php");
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
    <title>แก้ไขพนักงาน</title>
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
                        <a class="nav-link" href="manage_employees.php">จัดการพนักงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">แก้ไขพนักงาน</h2>
        <form method="post">
            <input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $row['user_id']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="<?php echo $row['position']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>
            </div>
            <button type="submit" name="update_employee" class="btn btn-primary">อัปเดตพนักงาน</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>