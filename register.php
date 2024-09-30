<?php 

session_start();
require_once "components/database.php";

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    // ตรวจสอบว่ามีผู้ใช้ที่ใช้อีเมลนี้อยู่แล้วหรือไม่
    $user_check = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $user_check);
    $user = mysqli_fetch_assoc($result);

    if ($user && $user['email'] === $email) {
        // หากพบอีเมลซ้ำ ให้ตั้งค่าเซสชัน error
        $_SESSION['error'] = "อีเมลนี้มีผู้ใช้แล้ว";
        header("Location: index.php");
        exit();
    } else {
        // เข้ารหัสรหัสผ่าน
        $passwordenc = md5($password);

        // แทรกข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
        $query = "INSERT INTO user (email, password, fname, lname, role)
                    VALUES ('$email', '$passwordenc', '$fname', '$lname', 'student')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['success'] = "สมัครสมาชิกสำเร็จ";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "สมัครสมาชิกไม่สำเร็จ";
            header("Location: index.php");
            exit();
        }
    }

}
?>