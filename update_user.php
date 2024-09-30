<?php
session_start();
require_once "components/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['userid'];
    
    // เริ่มสร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูล
    $update_query = "UPDATE user SET";

    $fields_to_update = array();

    // ตรวจสอบ fname ว่ามีการกรอกข้อมูลหรือไม่
    if (!empty($_POST['fname'])) {
        $fname = $_POST['fname'];
        $fields_to_update[] = "fname = '$fname'";
        $_SESSION['fname'] = $fname; // อัปเดตค่าในเซสชัน
    }

    // ตรวจสอบ lname ว่ามีการกรอกข้อมูลหรือไม่
    if (!empty($_POST['lname'])) {
        $lname = $_POST['lname'];
        $fields_to_update[] = "lname = '$lname'";
        $_SESSION['lname'] = $lname; // อัปเดตค่าในเซสชัน
    }

    // ตรวจสอบรหัสผ่านใหม่ว่ามีการกรอกหรือไม่
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']); // เข้ารหัสรหัสผ่าน
        $fields_to_update[] = "password = '$password'";
    }

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (!empty($_FILES['profile_pic']['name'])) {
        // ลบไฟล์ภาพเก่าก่อน
        if (isset($_SESSION['profile_pic']) && file_exists('uploads/profiles/' . $_SESSION['profile_pic'])) {
            unlink('uploads/profiles/' . $_SESSION['profile_pic']); // ลบไฟล์เก่า
        }

        $profile_pic = $_FILES['profile_pic']['name'];
        $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
        
        // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        $profile_pic_ext = pathinfo($profile_pic, PATHINFO_EXTENSION);
        $unique_pic_name = uniqid() . '.' . $profile_pic_ext;
        
        $profile_pic_folder = 'uploads/profiles/' . $unique_pic_name;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($profile_pic_tmp, $profile_pic_folder)) {
            // อัปเดตรูปโปรไฟล์ในฐานข้อมูล
            $fields_to_update[] = "profile_pic = '$unique_pic_name'";
            $_SESSION['profile_pic'] = $unique_pic_name; // อัปเดต session ด้วยชื่อไฟล์ใหม่
        } else {
            $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
        }
    }

    // ถ้ามีฟิลด์ที่จะอัปเดต
    if (!empty($fields_to_update)) {
        $update_query .= ' ' . implode(', ', $fields_to_update) . " WHERE id = '$user_id'";
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            $_SESSION['success'] = "อัปเดตข้อมูลสำเร็จ";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
        }
    }

    header("Location: home.php");
    exit();
}
?>
