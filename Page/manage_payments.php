<?php
session_start();
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินเป็น admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// CRUD Operations

// Create
if (isset($_POST['add_payment'])) {
    $tenant_id = $_POST['tenant_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO payments (tenant_id, month_year, amount, payment_date, status) VALUES ('$tenant_id', '$month_year', '$amount', '$payment_date', '$status')";
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
    $sql = "SELECT * FROM payments WHERE tenant_id LIKE '%$search%' OR month_year LIKE '%$search%' OR status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM payments";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $tenant_id = $_POST['tenant_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "UPDATE payments SET tenant_id='$tenant_id', month_year='$month_year', amount='$amount', payment_date='$payment_date', status='$status' WHERE payment_id='$payment_id'";
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
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="tenant_id" class="form-label">Tenant ID</label>
                <input type="text" class="form-control" id="tenant_id" name="tenant_id" required>
            </div>
            <div class="mb-3">
                <label for="month_year" class="form-label">Month-Year</label>
                <input type="text" class="form-control" id="month_year" name="month_year" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" required>
            </div>
            <div class="mb-3">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
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
                    <th>Tenant ID</th>
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
                            <td><?php echo $row['tenant_id']; ?></td>
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