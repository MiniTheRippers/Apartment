<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $sql = "SELECT * FROM payments WHERE payment_id='$payment_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $tenant_id = $_POST['tenant_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "UPDATE payments SET tenant_id='$tenant_id', month_year='$month_year', amount='$amount', payment_date='$payment_date', status='$status' WHERE payment_id='$payment_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_payments.php");
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
    <title>แก้ไขการชำระเงิน</title>
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
                        <a class="nav-link" href="manage_payments.php">จัดการการชำระเงิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ล็อกเอ้า</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">แก้ไขการชำระเงิน</h2>
        <form method="post">
            <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">
            <div class="mb-3">
                <label for="tenant_id" class="form-label">Tenant ID</label>
                <input type="text" class="form-control" id="tenant_id" name="tenant_id" value="<?php echo $row['tenant_id']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="month_year" class="form-label">Month-Year</label>
                <input type="text" class="form-control" id="month_year" name="month_year" value="<?php echo $row['month_year']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" value="<?php echo $row['amount']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo $row['payment_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="จ่ายแล้ว" <?php echo ($row['status'] == 'จ่ายแล้ว') ? 'selected' : ''; ?>>จ่ายแล้ว</option>
                    <option value="ยังไม่จ่าย" <?php echo ($row['status'] == 'ยังไม่จ่าย') ? 'selected' : ''; ?>>ยังไม่จ่าย</option>
                </select>
            </div>
            <button type="submit" name="update_payment" class="btn btn-primary">อัปเดตการชำระเงิน</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>