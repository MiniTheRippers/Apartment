<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "apartment";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันแสดงข้อมูลผู้ใช้
function display_users() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    echo "<h3>Users</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['full_name']}</td>
                <td>{$row['role']}</td>
                <td>
                    <a href='?action=edit_user&id={$row['user_id']}'>Edit</a> |
                    <a href='?action=delete_user&id={$row['user_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลห้องพัก
function display_rooms() {
    global $conn;
    $sql = "SELECT * FROM rooms";
    $result = $conn->query($sql);
    echo "<h3>Rooms</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Room ID</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Rent Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['room_id']}</td>
                <td>{$row['room_number']}</td>
                <td>{$row['room_type']}</td>
                <td>{$row['rent_price']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?action=edit_room&id={$row['room_id']}'>Edit</a> |
                    <a href='?action=delete_room&id={$row['room_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลสัญญาเช่า
function display_contracts() {
    global $conn;
    $sql = "SELECT * FROM contracts";
    $result = $conn->query($sql);
    echo "<h3>Contracts</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Contract ID</th>
                <th>Tenant ID</th>
                <th>Room ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['contract_id']}</td>
                <td>{$row['tenant_id']}</td>
                <td>{$row['room_id']}</td>
                <td>{$row['start_date']}</td>
                <td>{$row['end_date']}</td>
                <td>
                    <a href='?action=edit_contract&id={$row['contract_id']}'>Edit</a> |
                    <a href='?action=delete_contract&id={$row['contract_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลพนักงาน
function display_employees() {
    global $conn;
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);
    echo "<h3>Employees</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Employee ID</th>
                <th>User ID</th>
                <th>Position</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['employee_id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['position']}</td>
                <td>{$row['phone']}</td>
                <td>
                    <a href='?action=edit_employee&id={$row['employee_id']}'>Edit</a> |
                    <a href='?action=delete_employee&id={$row['employee_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลบริการเสริม
function display_extra_services() {
    global $conn;
    $sql = "SELECT * FROM extra_services";
    $result = $conn->query($sql);
    echo "<h3>Extra Services</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Service ID</th>
                <th>Room ID</th>
                <th>Service Name</th>
                <th>Cost</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['service_id']}</td>
                <td>{$row['room_id']}</td>
                <td>{$row['service_name']}</td>
                <td>{$row['cost']}</td>
                <td>
                    <a href='?action=edit_extra_service&id={$row['service_id']}'>Edit</a> |
                    <a href='?action=delete_extra_service&id={$row['service_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลการชำระเงิน
function display_payments() {
    global $conn;
    $sql = "SELECT * FROM payments";
    $result = $conn->query($sql);
    echo "<h3>Payments</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Payment ID</th>
                <th>Tenant ID</th>
                <th>Month Year</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['payment_id']}</td>
                <td>{$row['tenant_id']}</td>
                <td>{$row['month_year']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['payment_date']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?action=edit_payment&id={$row['payment_id']}'>Edit</a> |
                    <a href='?action=delete_payment&id={$row['payment_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลการซ่อมแซม
function display_repairs() {
    global $conn;
    $sql = "SELECT * FROM repairs";
    $result = $conn->query($sql);
    echo "<h3>Repairs</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Repair ID</th>
                <th>Room ID</th>
                <th>Description</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['repair_id']}</td>
                <td>{$row['room_id']}</td>
                <td>{$row['description']}</td>
                <td>{$row['request_date']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?action=edit_repair&id={$row['repair_id']}'>Edit</a> |
                    <a href='?action=delete_repair&id={$row['repair_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลรายงาน
function display_reports() {
    global $conn;
    $sql = "SELECT * FROM reports";
    $result = $conn->query($sql);
    echo "<h3>Reports</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Report ID</th>
                <th>User ID</th>
                <th>Report Type</th>
                <th>Generated At</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['report_id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['report_type']}</td>
                <td>{$row['generated_at']}</td>
                <td>
                    <a href='?action=edit_report&id={$row['report_id']}'>Edit</a> |
                    <a href='?action=delete_report&id={$row['report_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันแสดงข้อมูลรีวิว
function display_reviews() {
    global $conn;
    $sql = "SELECT * FROM reviews";
    $result = $conn->query($sql);
    echo "<h3>Reviews</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Review ID</th>
                <th>Tenant ID</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Review Date</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['review_id']}</td>
                <td>{$row['tenant_id']}</td>
                <td>{$row['rating']}</td>
                <td>{$row['comment']}</td>
                <td>{$row['review_date']}</td>
                <td>
                    <a href='?action=edit_review&id={$row['review_id']}'>Edit</a> |
                    <a href='?action=delete_review&id={$row['review_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

function display_room_equipment() {
    global $conn;
    $sql = "SELECT * FROM room_equipment";
    $result = $conn->query($sql);
    echo "<h3>Room Equipment</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Equipment ID</th>
                <th>Room ID</th>
                <th>Equipment Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['equipment_id']}</td>
                <td>{$row['room_id']}</td>
                <td>{$row['equipment_name']}</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?action=edit_room_equipment&id={$row['equipment_id']}'>Edit</a> |
                    <a href='?action=delete_room_equipment&id={$row['equipment_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

function display_tenants() {
    global $conn;
    $sql = "SELECT * FROM tenants";
    $result = $conn->query($sql);
    echo "<h3>Tenants</h3>";
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Tenant ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Check-in Date</th>
                <th>Actions</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['tenant_id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['check_in_date']}</td>
                <td>
                    <a href='?action=edit_tenant&id={$row['tenant_id']}'>Edit</a> |
                    <a href='?action=delete_tenant&id={$row['tenant_id']}'>Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}

// ฟังก์ชันเพิ่มห้องพัก
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_room'])) {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $rent_price = $_POST['rent_price'];
    $status = $_POST['status'];

    $sql = "INSERT INTO rooms (room_number, room_type, rent_price, status) VALUES ('$room_number', '$room_type', '$rent_price', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Room added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มผู้ใช้
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);  
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];

    $sql_check = "SELECT * FROM users WHERE username = '$username'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "Username '$username' already exists. Please choose another one.";
    } else {
        $sql = "INSERT INTO users (username, password_hash, email, full_name, role) VALUES ('$username', '$password_hash', '$email', '$full_name', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "User created successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// ฟังก์ชันเพิ่มพนักงาน
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_employee'])) {
    $user_id = $_POST['user_id'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO employees (user_id, position, phone) VALUES ('$user_id', '$position', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Employee added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบพนักงาน
if (isset($_GET['action']) && $_GET['action'] == 'delete_employee' && isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $sql = "DELETE FROM employees WHERE employee_id = '$employee_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Employee deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มบริการเสริม
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_extra_service'])) {
    $tenant_id = $_POST['tenant_id'];
    $service_name = $_POST['service_name'];
    $cost = $_POST['cost'];

    $sql = "INSERT INTO extra_services (tenant_id, service_name, cost) VALUES ('$tenant_id', '$service_name', '$cost')";

    if ($conn->query($sql) === TRUE) {
        echo "Extra service added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบบริการเสริม
if (isset($_GET['action']) && $_GET['action'] == 'delete_extra_service' && isset($_GET['id'])) {
    $service_id = $_GET['id'];
    $sql = "DELETE FROM extra_services WHERE service_id = '$service_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Extra service deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขบริการเสริม
if (isset($_GET['action']) && $_GET['action'] == 'edit_extra_service' && isset($_GET['id'])) {
    $service_id = $_GET['id'];
    $sql = "SELECT * FROM extra_services WHERE service_id = '$service_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Extra Service</h3>
          <form method='POST' action=''>
              <input type='hidden' name='service_id' value='{$row['service_id']}'>
              Room ID: <input type='number' name='room_id' value='{$row['room_id']}' required><br>
              Service Name: <input type='text' name='service_name' value='{$row['service_name']}' required><br>
              Cost: <input type='number' step='0.01' name='cost' value='{$row['cost']}' required><br>
              <input type='submit' name='update_extra_service' value='Update Extra Service'>
          </form>";
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_extra_service'])) {
    $service_id = $_POST['service_id'];
    $room_id = $_POST['room_id'];
    $service_name = $_POST['service_name'];
    $cost = $_POST['cost'];

    $sql = "UPDATE extra_services SET room_id = '$room_id', service_name = '$service_name', cost = '$cost' WHERE service_id = '$service_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Extra service updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มการชำระเงิน
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_payment'])) {
    $tenant_id = $_POST['tenant_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO payments (tenant_id, month_year, amount, payment_date, status) 
            VALUES ('$tenant_id', '$month_year', '$amount', '$payment_date', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Payment added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบการชำระเงิน
if (isset($_GET['action']) && $_GET['action'] == 'delete_payment' && isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $sql = "DELETE FROM payments WHERE payment_id = '$payment_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Payment deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// ฟังก์ชันแก้ไขการชำระเงิน
if (isset($_GET['action']) && $_GET['action'] == 'edit_payment' && isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $sql = "SELECT * FROM payments WHERE payment_id = '$payment_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Payment</h3>
          <form method='POST' action=''>
              <input type='hidden' name='payment_id' value='{$row['payment_id']}'>
              Tenant ID: <input type='number' name='tenant_id' value='{$row['tenant_id']}' required><br>
              Month Year: <input type='text' name='month_year' value='{$row['month_year']}' required><br>
              Amount: <input type='number' step='0.01' name='amount' value='{$row['amount']}' required><br>
              Payment Date: <input type='date' name='payment_date' value='{$row['payment_date']}' required><br>
              Status: 
              <select name='status'>
                  <option value='จ่ายแล้ว' " . ($row['status'] == 'จ่ายแล้ว' ? 'selected' : '') . ">Paid</option>
                  <option value='ยังไม่จ่าย' " . ($row['status'] == 'ยังไม่จ่าย' ? 'selected' : '') . ">Unpaid</option>
              </select><br>
              <input type='submit' name='update_payment' value='Update Payment'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $tenant_id = $_POST['tenant_id'];
    $month_year = $_POST['month_year'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $sql = "UPDATE payments SET 
            tenant_id = '$tenant_id', 
            month_year = '$month_year', 
            amount = '$amount', 
            payment_date = '$payment_date', 
            status = '$status' 
            WHERE payment_id = '$payment_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Payment updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขผู้ใช้
if (isset($_GET['action']) && $_GET['action'] == 'edit_user' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit User</h3>
          <form method='POST' action=''>
              <input type='hidden' name='user_id' value='{$row['user_id']}'>
              Username: <input type='text' name='username' value='{$row['username']}' required><br>
              Email: <input type='email' name='email' value='{$row['email']}' required><br>
              Full Name: <input type='text' name='full_name' value='{$row['full_name']}' required><br>
              Role: 
              <select name='role'>
                  <option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                  <option value='tenant' " . ($row['role'] == 'tenant' ? 'selected' : '') . ">Tenant</option>
                  <option value='employee' " . ($row['role'] == 'employee' ? 'selected' : '') . ">Employee</option>
              </select><br>
              <input type='submit' name='update_user' value='Update User'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET 
            username = '$username', 
            email = '$email', 
            full_name = '$full_name', 
            role = '$role' 
            WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขห้องพัก
if (isset($_GET['action']) && $_GET['action'] == 'edit_room' && isset($_GET['id'])) {
    $room_id = $_GET['id'];
    $sql = "SELECT * FROM rooms WHERE room_id = '$room_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Room</h3>
          <form method='POST' action=''>
              <input type='hidden' name='room_id' value='{$row['room_id']}'>
              Room Number: <input type='text' name='room_number' value='{$row['room_number']}' required><br>
              Room Type: <input type='text' name='room_type' value='{$row['room_type']}'><br>
              Rent Price: <input type='number' step='0.01' name='rent_price' value='{$row['rent_price']}' required><br>
              Status: 
              <select name='status'>
                  <option value='ว่าง' " . ($row['status'] == 'ว่าง' ? 'selected' : '') . ">Available</option>
                  <option value='มีคนเช่า' " . ($row['status'] == 'มีคนเช่า' ? 'selected' : '') . ">Occupied</option>
              </select><br>
              <input type='submit' name='update_room' value='Update Room'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_room'])) {
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $rent_price = $_POST['rent_price'];
    $status = $_POST['status'];

    $sql = "UPDATE rooms SET 
            room_number = '$room_number', 
            room_type = '$room_type', 
            rent_price = '$rent_price', 
            status = '$status' 
            WHERE room_id = '$room_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Room updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มการซ่อมแซม
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_repair'])) {
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO repairs (room_id, description, request_date, status) 
            VALUES ('$room_id', '$description', '$request_date', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Repair added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบการซ่อมแซม
if (isset($_GET['action']) && $_GET['action'] == 'delete_repair' && isset($_GET['id'])) {
    $repair_id = $_GET['id'];
    $sql = "DELETE FROM repairs WHERE repair_id = '$repair_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Repair deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขการซ่อมแซม
if (isset($_GET['action']) && $_GET['action'] == 'edit_repair' && isset($_GET['id'])) {
    $repair_id = $_GET['id'];
    $sql = "SELECT * FROM repairs WHERE repair_id = '$repair_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Repair</h3>
          <form method='POST' action=''>
              <input type='hidden' name='repair_id' value='{$row['repair_id']}'>
              Room ID: <input type='number' name='room_id' value='{$row['room_id']}' required><br>
              Description: <textarea name='description' required>{$row['description']}</textarea><br>
              Request Date: <input type='date' name='request_date' value='{$row['request_date']}' required><br>
              Status: 
              <select name='status'>
                  <option value='รอซ่อม' " . ($row['status'] == 'รอซ่อม' ? 'selected' : '') . ">Pending</option>
                  <option value='กำลังซ่อม' " . ($row['status'] == 'กำลังซ่อม' ? 'selected' : '') . ">In Progress</option>
                  <option value='ซ่อมเสร็จแล้ว' " . ($row['status'] == 'ซ่อมเสร็จแล้ว' ? 'selected' : '') . ">Completed</option>
              </select><br>
              <input type='submit' name='update_repair' value='Update Repair'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_repair'])) {
    $repair_id = $_POST['repair_id'];
    $room_id = $_POST['room_id'];
    $description = $_POST['description'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "UPDATE repairs SET 
            room_id = '$room_id', 
            description = '$description', 
            request_date = '$request_date', 
            status = '$status' 
            WHERE repair_id = '$repair_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Repair updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มรายงาน
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_report'])) {
    $user_id = $_POST['user_id'];
    $report_type = $_POST['report_type'];

    $sql = "INSERT INTO reports (user_id, report_type) 
            VALUES ('$user_id', '$report_type')";

    if ($conn->query($sql) === TRUE) {
        echo "Report added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบรายงาน
if (isset($_GET['action']) && $_GET['action'] == 'delete_report' && isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $sql = "DELETE FROM reports WHERE report_id = '$report_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Report deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขรายงาน
if (isset($_GET['action']) && $_GET['action'] == 'edit_report' && isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $sql = "SELECT * FROM reports WHERE report_id = '$report_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Report</h3>
          <form method='POST' action=''>
              <input type='hidden' name='report_id' value='{$row['report_id']}'>
              User ID: <input type='number' name='user_id' value='{$row['user_id']}' required><br>
              Report Type: <input type='text' name='report_type' value='{$row['report_type']}' required><br>
              <input type='submit' name='update_report' value='Update Report'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_report'])) {
    $report_id = $_POST['report_id'];
    $user_id = $_POST['user_id'];
    $report_type = $_POST['report_type'];

    $sql = "UPDATE reports SET 
            user_id = '$user_id', 
            report_type = '$report_type' 
            WHERE report_id = '$report_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Report updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันเพิ่มรีวิว
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_review'])) {
    $tenant_id = $_POST['tenant_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $review_date = $_POST['review_date'];

    $sql = "INSERT INTO reviews (tenant_id, rating, comment, review_date) 
            VALUES ('$tenant_id', '$rating', '$comment', '$review_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันลบรีวิว
if (isset($_GET['action']) && $_GET['action'] == 'delete_review' && isset($_GET['id'])) {
    $review_id = $_GET['id'];
    $sql = "DELETE FROM reviews WHERE review_id = '$review_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Review deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// ฟังก์ชันแก้ไขรีวิว
if (isset($_GET['action']) && $_GET['action'] == 'edit_review' && isset($_GET['id'])) {
    $review_id = $_GET['id'];
    $sql = "SELECT * FROM reviews WHERE review_id = '$review_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Review</h3>
          <form method='POST' action=''>
              <input type='hidden' name='review_id' value='{$row['review_id']}'>
              Tenant ID: <input type='number' name='tenant_id' value='{$row['tenant_id']}' required><br>
              Rating: <input type='number' name='rating' value='{$row['rating']}' required><br>
              Comment: <textarea name='comment' required>{$row['comment']}</textarea><br>
              Review Date: <input type='date' name='review_date' value='{$row['review_date']}' required><br>
              <input type='submit' name='update_review' value='Update Review'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_review'])) {
    $review_id = $_POST['review_id'];
    $tenant_id = $_POST['tenant_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $review_date = $_POST['review_date'];

    $sql = "UPDATE reviews SET 
            tenant_id = '$tenant_id', 
            rating = '$rating', 
            comment = '$comment', 
            review_date = '$review_date' 
            WHERE review_id = '$review_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Review updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_room_equipment'])) {
    $room_id = $_POST['room_id'];
    $equipment_name = $_POST['equipment_name'];
    $status = $_POST['status'];

    $sql = "INSERT INTO room_equipment (room_id, equipment_name, status) 
            VALUES ('$room_id', '$equipment_name', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "Room equipment added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete_room_equipment' && isset($_GET['id'])) {
    $equipment_id = $_GET['id'];
    $sql = "DELETE FROM room_equipment WHERE equipment_id = '$equipment_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Room equipment deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'edit_room_equipment' && isset($_GET['id'])) {
    $equipment_id = $_GET['id'];
    $sql = "SELECT * FROM room_equipment WHERE equipment_id = '$equipment_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Room Equipment</h3>
          <form method='POST' action=''>
              <input type='hidden' name='equipment_id' value='{$row['equipment_id']}'>
              Room ID: <input type='number' name='room_id' value='{$row['room_id']}' required><br>
              Equipment Name: <input type='text' name='equipment_name' value='{$row['equipment_name']}' required><br>
              Status: 
              <select name='status'>
                  <option value='ใช้งานได้' " . ($row['status'] == 'ใช้งานได้' ? 'selected' : '') . ">ใช้งานได้</option>
                  <option value='เสียหาย' " . ($row['status'] == 'เสียหาย' ? 'selected' : '') . ">เสียหาย</option>
                  <option value='ต้องเปลี่ยน' " . ($row['status'] == 'ต้องเปลี่ยน' ? 'selected' : '') . ">ต้องเปลี่ยน</option>
              </select><br>
              <input type='submit' name='update_room_equipment' value='Update Room Equipment'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_room_equipment'])) {
    $equipment_id = $_POST['equipment_id'];
    $room_id = $_POST['room_id'];
    $equipment_name = $_POST['equipment_name'];
    $status = $_POST['status'];

    $sql = "UPDATE room_equipment SET 
            room_id = '$room_id', 
            equipment_name = '$equipment_name', 
            status = '$status' 
            WHERE equipment_id = '$equipment_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Room equipment updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_tenant'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $check_in_date = $_POST['check_in_date'];

    $sql = "INSERT INTO tenants (user_id, name, phone, address, check_in_date) 
            VALUES ('$user_id', '$name', '$phone', '$address', '$check_in_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Tenant added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete_tenant' && isset($_GET['id'])) {
    $tenant_id = $_GET['id'];
    $sql = "DELETE FROM tenants WHERE tenant_id = '$tenant_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Tenant deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'edit_tenant' && isset($_GET['id'])) {
    $tenant_id = $_GET['id'];
    $sql = "SELECT * FROM tenants WHERE tenant_id = '$tenant_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    echo "<h3>Edit Tenant</h3>
          <form method='POST' action=''>
              <input type='hidden' name='tenant_id' value='{$row['tenant_id']}'>
              User ID: <input type='number' name='user_id' value='{$row['user_id']}' required><br>
              Name: <input type='text' name='name' value='{$row['name']}' required><br>
              Phone: <input type='text' name='phone' value='{$row['phone']}' required><br>
              Address: <textarea name='address' required>{$row['address']}</textarea><br>
              Check-in Date: <input type='date' name='check_in_date' value='{$row['check_in_date']}' required><br>
              <input type='submit' name='update_tenant' value='Update Tenant'>
          </form>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_tenant'])) {
    $tenant_id = $_POST['tenant_id'];
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $check_in_date = $_POST['check_in_date'];

    $sql = "UPDATE tenants SET 
            user_id = '$user_id', 
            name = '$name', 
            phone = '$phone', 
            address = '$address', 
            check_in_date = '$check_in_date' 
            WHERE tenant_id = '$tenant_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Tenant updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD for Multiple Tables</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        color: #333;
    }
    .container {
        width: 90%;
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    h2, h3 {
        color: #007bff;
        margin-bottom: 20px;
    }
    h2 {
        text-align: center;
        font-size: 2.5em;
        margin-bottom: 30px;
    }
    h3 {
        font-size: 1.8em;
        margin-top: 30px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    table th, table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    table th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    table tr:hover {
        background-color: #f1f1f1;
    }
    input[type="text"], input[type="password"], input[type="email"], input[type="number"], textarea, select {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1em;
    }
    input[type="submit"], .btn {
        padding: 12px 24px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover, .btn:hover {
        background-color: #0056b3;
    }
    .form-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .form-section h3 {
        margin-top: 0;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 20px;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .logout-btn {
        float: right;
        margin-top: -60px;
    }

     /* สไตล์พื้นฐานสำหรับปุ่ม Edit และ Delete */
     .btn-edit, .btn-delete {
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9em;
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        display: inline-block;
        text-align: center;
        cursor: pointer;
        border: none;
        outline: none;
    }

    /* สไตล์ปุ่ม Edit */
    .btn-edit {
        background-color: #28a745; /* สีเขียว */
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .btn-edit:hover {
        background-color: #218838; /* สีเขียวเข้มเมื่อโฮเวอร์ */
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .btn-edit:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* สไตล์ปุ่ม Delete */
    .btn-delete {
        background-color: #dc3545; /* สีแดง */
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .btn-delete:hover {
        background-color: #c82333; /* สีแดงเข้มเมื่อโฮเวอร์ */
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .btn-delete:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* เพิ่มระยะห่างระหว่างปุ่ม */
    .btn-edit, .btn-delete {
        margin-right: 5px;
    }
    
</style>
</head>
<body>

    <div class="container">
        <h2>Manage All Data</h2>
        <a href="Login.php" class="btn btn-primary profile-btn">Logout</a>

        <!-- ฟอร์มสร้างผู้ใช้ -->
        <h3>Create New User</h3>
        <form method="POST" action="">
            Username: <input type="text" name="username" required><br>
            Password: <input type="password" name="password" required><br>
            Email: <input type="email" name="email" required><br>
            Full Name: <input type="text" name="full_name" required><br>
            Role: 
            <select name="role">
                <option value="admin">Admin</option>
                <option value="tenant">Tenant</option>
                <option value="employee">Employee</option>
            </select><br>
            <input type="submit" name="create_user" value="Create User">
        </form>

        <!-- ฟอร์มสร้างห้องพัก -->
        <h3>Create New Room</h3>
        <form method="POST" action="">
            Room Number: <input type="text" name="room_number" required><br>
            Room Type: <input type="text" name="room_type"><br>
            Rent Price: <input type="number" step="0.01" name="rent_price" required><br>
            Status:
            <select name="status">
                <option value="ว่าง">Available</option>
                <option value="มีคนเช่า">Occupied</option>
            </select><br>
            <input type="submit" name="create_room" value="Add Room">
        </form>

        <!-- ฟอร์มสร้างพนักงาน -->
        <h3>Create New Employee</h3>
        <form method="POST" action="">
            User ID: <input type="number" name="user_id" required><br>
            Position: <input type="text" name="position" required><br>
            Phone: <input type="text" name="phone" required><br>
            <input type="submit" name="create_employee" value="Add Employee">
        </form>

        <!-- ฟอร์มสร้างบริการเสริม -->
        <h3>Create New Extra Service</h3>
        <form method="POST" action="">
            Tenant ID: <input type="number" name="tenant_id" required><br>
            Service Name: <input type="text" name="service_name" required><br>
            Cost: <input type="number" step="0.01" name="cost" required><br>
            <input type="submit" name="create_extra_service" value="Add Extra Service">
        </form>

        <!-- ฟอร์มสร้างการชำระเงิน -->
        <h3>Create New Payment</h3>
        <form method="POST" action="">
            Tenant ID: <input type="number" name="tenant_id" required><br>
            Month Year (YYYY-MM): <input type="text" name="month_year" required><br>
            Amount: <input type="number" step="0.01" name="amount" required><br>
            Payment Date: <input type="date" name="payment_date" required><br>
            Status: 
        <select name="status">
            <option value="จ่ายแล้ว">Paid</option>
            <option value="ยังไม่จ่าย">Unpaid</option>
        </select><br>
            <input type="submit" name="create_payment" value="Add Payment">
        </form>

        <!-- ฟอร์มสร้างการซ่อมแซม -->
        <h3>Create New Repair</h3>
        <form method="POST" action="">
            Room ID: <input type="number" name="room_id" required><br>
            Description: <textarea name="description" required></textarea><br>
            Request Date: <input type="date" name="request_date" required><br>
            Status: 
        <select name="status">
            <option value="รอซ่อม">Pending</option>
            <option value="กำลังซ่อม">In Progress</option>
            <option value="ซ่อมเสร็จแล้ว">Completed</option>
        </select><br>
            <input type="submit" name="create_repair" value="Add Repair">
        </form>

        <!-- ฟอร์มสร้างรายงาน -->
        <h3>Create New Report</h3>
        <form method="POST" action="">
            User ID: <input type="number" name="user_id" required><br>
            Report Type: <input type="text" name="report_type" required><br>
            <input type="submit" name="create_report" value="Add Report">
        </form>

        <!-- ฟอร์มสร้างรีวิว -->
        <h3>Create New Review</h3>
        <form method="POST" action="">
            Tenant ID: <input type="number" name="tenant_id" required><br>
            Rating: <input type="number" name="rating" required><br>
            Comment: <textarea name="comment" required></textarea><br>
            Review Date: <input type="date" name="review_date" required><br>
            <input type="submit" name="create_review" value="Add Review">
        </form>

        <h3>Create New Room Equipment</h3>
        <form method="POST" action="">
            Room ID: <input type="number" name="room_id" required><br>
            Equipment Name: <input type="text" name="equipment_name" required><br>
            Status: 
            <select name="status">
                <option value="ใช้งานได้">ใช้งานได้</option>
                <option value="เสียหาย">เสียหาย</option>
                <option value="ต้องเปลี่ยน">ต้องเปลี่ยน</option>
            </select><br>
            <input type="submit" name="create_room_equipment" value="Add Room Equipment">
        </form>

        <h3>Create New Tenant</h3>
        <form method="POST" action="">
            User ID: <input type="number" name="user_id" required><br>
            Name: <input type="text" name="name" required><br>
            Phone: <input type="text" name="phone" required><br>
            Address: <textarea name="address" required></textarea><br>
            Check-in Date: <input type="date" name="check_in_date" required><br>
            <input type="submit" name="create_tenant" value="Add Tenant">
        </form>

        <!-- แสดงข้อมูลจากตารางต่างๆ -->
        <?php
            display_users();
            display_rooms();
            display_contracts();
            display_employees();
            display_extra_services();
            display_payments();
            display_repairs();
            display_reports();
            display_reviews();
            display_room_equipment();
            display_tenants();  
        ?>
    </div>

</body>
</html>
