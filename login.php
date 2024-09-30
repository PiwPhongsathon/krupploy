<?php

session_start();
include('components/database.php');
if (isset($_POST['login'])) {

    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $passwordenc = md5($password); // เข้ารหัสรหัสผ่านให้ตรงกับที่เก็บในฐานข้อมูล

    // ตรวจสอบข้อมูลผู้ใช้ในฐานข้อมูล
    $query = "SELECT * FROM user WHERE email = '$email' AND password = '$passwordenc' ";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // ตั้งค่าเซสชัน
        $_SESSION['userid'] = $row['id'];
        $_SESSION['user'] = $row['fname'] . " " . $row['lname'];
        $_SESSION['role'] = $row['role'];

        // เปลี่ยนเส้นทางตามบทบาทของผู้ใช้
        if ($_SESSION['role'] == 'teacher') {
            header("Location: teacher/home.php");
        } elseif ($_SESSION['role'] == 'student') {
            header("Location: home.php");
        } else {
            // บทบาทที่ไม่รู้จัก
            header("Location: index.php");
        }
        exit(); // ออกจากสคริปต์หลังจากการเปลี่ยนเส้นทาง
    } else {
        $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
        header("Location: index.php");
        exit(); // ออกจากสคริปต์หลังจากการเปลี่ยนเส้นทาง
    }
}
