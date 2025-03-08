<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลผู้เช่าทั้งหมด
$tenants_sql = "SELECT tenant_id, name FROM tenants";
$tenants_result = $conn->query($tenants_sql);

// CRUD Operations

// Create
if (isset($_POST['add_payment'])) {
    $user_id = $_POST['user_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO payments (user_id, month_year, amount, payment_date, status) VALUES ('$user_id', '$month_year', '$amount', '$payment_date', '$status')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ตรวจสอบการค้นหา
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Read
if (!empty($search)) {
    $sql = "SELECT * FROM payments WHERE user_id LIKE '%$search%' OR month_year LIKE '%$search%' OR status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM payments";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $user_id = $_POST['user_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "UPDATE payments SET user_id='$user_id', month_year='$month_year', amount='$amount', payment_date='$payment_date', status='$status' WHERE payment_id='$payment_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $payment_id = $_GET['delete'];
    $sql = "DELETE FROM payments WHERE payment_id='$payment_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการการชำระเงิน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">จัดการการชำระเงิน</h2>
        <!-- ปุ่มกลับ -->
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">กลับ</a>

        <!-- Form for adding new payment -->
        <form method="post" action="manage_payments.php" class="mb-4">
            <div class="mb-3">
                <label for="user_id" class="form-label">ผู้เช่า</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">เลือกผู้เช่า</option>
                    <?php while($tenant = $tenants_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($tenant['tenant_id']); ?>">
                            <?php echo htmlspecialchars($tenant['tenant_id'] . ' - ' . $tenant['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="month_year" class="form-label">เดือน-ปี</label>
                <input type="month" class="form-control" id="month_year" name="month_year" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">จำนวนเงิน</label>
                <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="payment_date" class="form-label">วันที่ชำระ</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">เลือกสถานะ</option>
                    <option value="จ่ายแล้ว">จ่ายแล้ว</option>
                    <option value="ยังไม่จ่าย">ยังไม่จ่าย</option>
                </select>
            </div>
            <button type="submit" name="add_payment" class="btn btn-primary">เพิ่มการชำระเงิน</button>
        </form>

        <!-- ฟอร์มค้นหา -->
        <form action="manage_payments.php" method="get" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาการชำระเงิน..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">ค้นหา</button>
            </div>
        </form>

        <!-- Display payments -->
        <table class="table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>User ID</th>
                    <th>Month-Year</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['payment_id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['month_year']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['payment_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit_payment.php?id=<?php echo $row['payment_id']; ?>" class="btn btn-warning">แก้ไข</a>
                                <a href="?delete=<?php echo $row['payment_id']; ?>" class="btn btn-danger">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">ไม่มีข้อมูลการชำระเงิน</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
