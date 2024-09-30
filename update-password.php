<?php
if (isset($_POST['token']) && isset($_POST['new-password'])) {
    $token = $_POST['token'];
    $new_password = $_POST['new-password'];

    // เชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli('localhost', 'username', 'password', 'database_name'); // แก้ไขให้ตรงกับข้อมูลของคุณ

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // อัปเดตรหัสผ่านในฐานข้อมูล
    // คุณอาจต้องเก็บโทเค็นนี้ในฐานข้อมูลเพื่อเปรียบเทียบ
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // แฮชรหัสผ่านใหม่

    // แก้ไข SQL ให้เหมาะสมกับการอัปเดต
    $sql = "UPDATE users SET password='$hashed_password' WHERE email=(SELECT email FROM users WHERE token='$token')";
    
    if ($conn->query($sql) === TRUE) {
        echo "รหัสผ่านของคุณถูกรีเซ็ตเรียบร้อยแล้ว!";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน: " . $conn->error;
    }

    $conn->close();
}
?>