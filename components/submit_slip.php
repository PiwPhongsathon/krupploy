<?php
session_start();
require_once "database.php";
date_default_timezone_set('Asia/Bangkok');

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $student_id = $_SESSION['userid'];

    // ตรวจสอบว่าผู้ใช้ได้ซื้อคอร์สนี้แล้วหรือยัง
    $check_query = "SELECT * FROM course_purchases WHERE student_id = '$student_id' AND course_id = '$course_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'message' => 'คุณได้ซื้อคอร์สนี้ไปแล้ว!']);
    } else {
        $purchase_date = date("Y-m-d H:i:s");

        // ดึงราคาจาก table courses
        $course_query = "SELECT price FROM courses WHERE id = '$course_id'";
        $course_result = mysqli_query($conn, $course_query);

        if ($course_result && mysqli_num_rows($course_result) > 0) {
            $course_data = mysqli_fetch_assoc($course_result);
            $course_price = $course_data['price'];

            // Handle the slip image upload
            $slip_image = $_FILES['slip_image']['name'];
            $slip_image_tmp = $_FILES['slip_image']['tmp_name'];
            $slip_image_ext = pathinfo($slip_image, PATHINFO_EXTENSION);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
            $max_file_size = 5 * 1024 * 1024; // 5 MB

            // ตรวจสอบประเภทไฟล์
            if (!in_array(strtolower($slip_image_ext), $allowed_extensions)) {
                echo json_encode(['success' => false, 'message' => 'ประเภทไฟล์ไม่ถูกต้อง! อนุญาตเฉพาะไฟล์ jpg, jpeg, png, และ pdf เท่านั้น']);
                exit;
            }

            // ตรวจสอบขนาดไฟล์
            if ($_FILES['slip_image']['size'] > $max_file_size) {
                echo json_encode(['success' => false, 'message' => 'ไฟล์มีขนาดใหญ่เกินไป! อนุญาตเฉพาะไฟล์ที่มีขนาดไม่เกิน 5 MB']);
                exit;
            }

            $slip_image_new_name = uniqid() . '.' . $slip_image_ext;
            $slip_image_folder = '../uploads/slips/' . $slip_image_new_name;

            // อัปโหลดไฟล์
            if (move_uploaded_file($slip_image_tmp, $slip_image_folder)) {
                // Insert into the database
                $query = "INSERT INTO course_purchases (student_id, course_id, purchase_date, slip_image, price, status)
                          VALUES ('$student_id', '$course_id', '$purchase_date', '$slip_image_new_name', '$course_price', 'รอตรวจสอบ')";
                if (mysqli_query($conn, $query)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปโหลดสลิปการโอนเงินได้']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลคอร์ส']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูล']);
}

mysqli_close($conn);

