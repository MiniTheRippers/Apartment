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
if (isset($_POST['add_repair'])) {
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO repairs (room_id, description, request_date, status) VALUES ('$room_id', '$description', '$request_date', '$status')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ตรวจสอบการค้นหา
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ดึงข้อมูลการซ่อมแซม
if (!empty($search)) {
    $sql = "SELECT r.*, rm.room_number 
            FROM repairs r 
            JOIN rooms rm ON r.room_id = rm.room_id 
            WHERE r.room_id LIKE '%$search%' 
            OR r.description LIKE '%$search%' 
            OR r.status LIKE '%$search%' 
            ORDER BY r.request_date DESC";
} else {
    $sql = "SELECT r.*, rm.room_number 
            FROM repairs r 
            JOIN rooms rm ON r.room_id = rm.room_id 
            ORDER BY r.request_date DESC";
}
$result = $conn->query($sql);

// Update
if (isset($_POST['update_repair'])) {
    $repair_id = $_POST['repair_id'];
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "UPDATE repairs SET room_id='$room_id', description='$description', request_date='$request_date', status='$status' WHERE repair_id='$repair_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete
if (isset($_GET['delete'])) {
    $repair_id = $_GET['delete'];
    $sql = "DELETE FROM repairs WHERE repair_id='$repair_id'";
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
    <title>จัดการการซ่อมแซม - ระบบจัดการอพาร์ทเม้นท์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-family: 'Kanit', sans-serif;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .nav-link:hover {
            opacity: 0.9;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: var(--dark-color);
        }

        .btn-warning:hover {
            background-color: #ffca2c;
            border-color: #ffc720;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
            transform: translateY(-2px);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--light-color);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status-pending {
            background-color: var(--warning-color);
            color: var(--dark-color);
        }

        .status-in-progress {
            background-color: var(--primary-color);
            color: white;
        }

        .status-completed {
            background-color: var(--success-color);
            color: white;
        }

        .search-section {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .footer {
            background-color: white;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-building me-2"></i>ระบบจัดการอพาร์ทเม้นท์
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_dashboard.php">
                            <i class="fas fa-home me-1"></i>แดชบอร์ด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_rooms.php">
                            <i class="fas fa-door-open me-1"></i>จัดการห้อง
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_repairs.php">
                            <i class="fas fa-wrench me-1"></i>จัดการซ่อมแซม
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>ออกจากระบบ
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="main-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-wrench me-2"></i>จัดการการซ่อมแซม
                </h2>
                <a href="add_repair.php" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>เพิ่มรายการซ่อมแซม
                </a>
            </div>

            <!-- ฟอร์มค้นหา -->
            <div class="search-section mb-4">
                <form action="manage_repairs.php" method="get">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search" 
                               placeholder="ค้นหารายการซ่อมแซม..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>ค้นหา
                        </button>
                    </div>
                </form>
            </div>

            <!-- ตารางแสดงรายการซ่อมแซม -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ห้อง</th>
                                    <th scope="col">รายละเอียด</th>
                                    <th scope="col">วันที่แจ้ง</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-door-closed text-primary me-2"></i>
                                                <?php echo htmlspecialchars($row['room_number']); ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                                <?php echo htmlspecialchars($row['description']); ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-calendar text-primary me-2"></i>
                                                <?php echo date('d/m/Y', strtotime($row['request_date'])); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch($row['status']) {
                                                    case 'รอซ่อม':
                                                        $status_class = 'status-pending';
                                                        break;
                                                    case 'กำลังซ่อม':
                                                        $status_class = 'status-in-progress';
                                                        break;
                                                    case 'ซ่อมเสร็จแล้ว':
                                                        $status_class = 'status-completed';
                                                        break;
                                                }
                                                ?>
                                                <span class="status-badge <?php echo $status_class; ?>">
                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_repair.php?id=<?php echo $row['repair_id']; ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit me-1"></i>แก้ไข
                                                </a>
                                                <a href="delete_repair.php?id=<?php echo $row['repair_id']; ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการซ่อมแซมนี้?')">
                                                    <i class="fas fa-trash me-1"></i>ลบ
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <i class="fas fa-info-circle me-2"></i>ไม่พบรายการซ่อมแซม
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-3">
        <div class="container text-center">
            <span class="text-muted">© 2024 ระบบจัดการอพาร์ทเม้นท์. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>
