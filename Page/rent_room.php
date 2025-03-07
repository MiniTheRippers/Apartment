<?php
session_start(); // เริ่ม session เพื่อดึงข้อมูลผู้ใช้
include '../DB/db_connect.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalHtml = `
                <div class='modal fade' id='loginModal' tabindex='-1' role='dialog' aria-labelledby='loginModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='loginModalLabel'>ข้อผิดพลาด</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p class='text-center'>กรุณาล็อกอินก่อนเช่าห้องพัก</p>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                                <a href='login.php' class='btn btn-primary'>ไปที่หน้าเข้าสู่ระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                var modal = new bootstrap.Modal(document.getElementById('loginModal'));
                modal.show();
            });
          </script>";
    exit();
}

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $user_id = $_SESSION['user_id']; // ดึง user_id จาก session

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();

        if ($room['status'] == 'ว่าง') {
            // เปลี่ยนสถานะห้องเป็น 'มีคนเช่า' และบันทึก user_id
            $update_stmt = $conn->prepare("UPDATE rooms SET status = 'มีคนเช่า', user_id = ? WHERE room_id = ?");
            $update_stmt->bind_param("ii", $user_id, $room_id);

            if ($update_stmt->execute()) {
                // แสดง modal แทน alert
                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modalHtml = `
                        <div class='modal fade' id='successModal' tabindex='-1' role='dialog' aria-labelledby='successModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='successModalLabel'>สำเร็จ</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p class='text-center'>คุณได้เช่าห้องเรียบร้อยแล้ว</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                                        <a href='Dashboard.php' class='btn btn-primary'>ไปที่ Dashboard</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                        var modal = new bootstrap.Modal(document.getElementById('successModal'));
                        modal.show();
                    });
                </script>
                ";
            } else {
                echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modalHtml = `
                        <div class='modal fade' id='errorModal' tabindex='-1' role='dialog' aria-labelledby='errorModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='errorModalLabel'>ข้อผิดพลาด</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <p class='text-center'>เกิดข้อผิดพลาดในการอัปเดตสถานะห้อง</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                                        <a href='Dashboard.php' class='btn btn-primary'>ไปที่ Dashboard</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                        var modal = new bootstrap.Modal(document.getElementById('errorModal'));
                        modal.show();
                    });
                </script>
                ";
            }
            $update_stmt->close();
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modalHtml = `
                    <div class='modal fade' id='notAvailableModal' tabindex='-1' role='dialog' aria-labelledby='notAvailableModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='notAvailableModalLabel'>ข้อผิดพลาด</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    <p class='text-center'>ห้องนี้ไม่ว่างแล้ว</p>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                                    <a href='Dashboard.php' class='btn btn-primary'>ไปที่ Dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                    var modal = new bootstrap.Modal(document.getElementById('notAvailableModal'));
                    modal.show();
                });
            </script>
            ";
        }
    } else {
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalHtml = `
                <div class='modal fade' id='roomNotFoundModal' tabindex='-1' role='dialog' aria-labelledby='roomNotFoundModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='roomNotFoundModalLabel'>ข้อผิดพลาด</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p class='text-center'>ไม่พบห้องที่เลือก</p>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                                <a href='Dashboard.php' class='btn btn-primary'>ไปที่ Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                var modal = new bootstrap.Modal(document.getElementById('roomNotFoundModal'));
                modal.show();
            });
        </script>
        ";
    }
    $stmt->close();
} else {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalHtml = `
            <div class='modal fade' id='roomNotSelectedModal' tabindex='-1' role='dialog' aria-labelledby='roomNotSelectedModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='roomNotSelectedModalLabel'>ข้อผิดพลาด</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <p class='text-center'>ไม่พบห้องที่เลือก</p>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>ปิด</button>
                            <a href='Dashboard.php' class='btn btn-primary'>ไปที่ Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            var modal = new bootstrap.Modal(document.getElementById('roomNotSelectedModal'));
            modal.show();
        });
    </script>
    ";
}

$conn->close();
?>
