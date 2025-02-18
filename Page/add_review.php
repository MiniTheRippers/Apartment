<?php
session_start();
include 'C:\xampp\htdocs\LabApartment\DB\db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$room_id = $_GET['room_id'];

$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ? AND user_id = ?");
$stmt->bind_param("ii", $room_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('คุณไม่มีสิทธิ์เขียนรีวิวห้องนี้'); window.location='Dashboard.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $review_date = date('Y-m-d');

    $insert_stmt = $conn->prepare("INSERT INTO reviews (tenant_id, rating, comment, review_date) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("iiss", $user_id, $rating, $comment, $review_date);

    if ($insert_stmt->execute()) {
        echo "<script>alert('เขียนรีวิวเรียบร้อยแล้ว'); window.location='Dashboard.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเขียนรีวิว'); window.location='Dashboard.php';</script>";
    }
    $insert_stmt->close();
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เขียนรีวิว</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>เขียนรีวิว</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="rating" class="form-label">คะแนน (1-5)</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">ความคิดเห็น</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">ส่งรีวิว</button>
            <a href="Dashboard.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>
