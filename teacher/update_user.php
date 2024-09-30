<?php
session_start();
require_once "../components/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'teacher') {
    $user_id = $_SESSION['userid'];

    $update_query = "UPDATE user SET";
    $fields_to_update = array();

    if (!empty($_POST['fname'])) {
        $fname = $_POST['fname'];
        $fields_to_update[] = "fname = '$fname'";
        $_SESSION['fname'] = $fname;
    }

    if (!empty($_POST['lname'])) {
        $lname = $_POST['lname'];
        $fields_to_update[] = "lname = '$lname'";
        $_SESSION['lname'] = $lname;
    }

    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $fields_to_update[] = "password = '$password'";
    }

    if (!empty($_FILES['profile_pic']['name'])) {
        if (isset($_SESSION['profile_pic']) && file_exists('../uploads/profiles/' . $_SESSION['profile_pic'])) {
            unlink('../uploads/profiles/' . $_SESSION['profile_pic']);
        }

        $profile_pic = $_FILES['profile_pic']['name'];
        $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];

        $profile_pic_ext = pathinfo($profile_pic, PATHINFO_EXTENSION);
        $unique_pic_name = uniqid() . '.' . $profile_pic_ext;

        $profile_pic_folder = '../uploads/profiles/' . $unique_pic_name;

        if (move_uploaded_file($profile_pic_tmp, $profile_pic_folder)) {
            $fields_to_update[] = "profile_pic = '$unique_pic_name'";
            $_SESSION['profile_pic'] = $unique_pic_name;
        } else {
            $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพได้";
        }
    }

    if (!empty($_FILES['bank_slip_image']['name'])) {
        if (isset($_SESSION['bank_slip_image']) && file_exists('../uploads/bank_slips/' . $_SESSION['bank_slip_image'])) {
            unlink('../uploads/bank_slips/' . $_SESSION['bank_slip_image']);
        }

        $bank_slip_image = $_FILES['bank_slip_image']['name'];
        $bank_slip_tmp = $_FILES['bank_slip_image']['tmp_name'];

        $bank_slip_ext = pathinfo($bank_slip_image, PATHINFO_EXTENSION);
        $unique_slip_name = uniqid() . '.' . $bank_slip_ext;

        $bank_slip_folder = '../uploads/bank_slips/' . $unique_slip_name;

        if (move_uploaded_file($bank_slip_tmp, $bank_slip_folder)) {
            $fields_to_update[] = "bank_slip_image = '$unique_slip_name'";
            $_SESSION['bank_slip_image'] = $unique_slip_name;
        } else {
            $_SESSION['error'] = "ไม่สามารถอัปโหลดรูปภาพสลิปธนาคารได้";
        }
    }
    
    // อัปเดตข้อมูลในฐานข้อมูล
    if (!empty($fields_to_update)) {
        $update_query = "UPDATE user SET " . implode(', ', $fields_to_update) . " WHERE id = '$user_id'";
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            // หากอัปเดตข้อมูลในฐานข้อมูลสำเร็จ ให้ทำการอัปเดตข้อมูลในเซสชันด้วย
            if (!empty($_POST['fname'])) {
                $_SESSION['fname'] = $_POST['fname'];
            }

            if (!empty($_POST['lname'])) {
                $_SESSION['lname'] = $_POST['lname'];
            }

            if (!empty($unique_pic_name)) {
                $_SESSION['profile_pic'] = $unique_pic_name; // อัปเดตรูปโปรไฟล์ในเซสชัน
            }

            $_SESSION['success'] = "อัปเดตข้อมูลสำเร็จ";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
        }
    }

    header("Location: ../teacher/home.php");
    exit();
}
?>
