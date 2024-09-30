<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบและเป็นครูผู้สอนหรือไม่
if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งค่า exercise_id และ course_id มา
if (isset($_POST['exercise_id']) && isset($_POST['course_id'])) {
    $exercise_id = $_POST['exercise_id'];
    $course_id = $_POST['course_id'];

    // ลบข้อมูลในตาราง exercise_answers ที่เชื่อมโยงกับคำถาม
    $delete_answers_query = "DELETE FROM exercise_answers WHERE question_id IN 
                            (SELECT id FROM exercise_questions WHERE exercise_id = '$exercise_id')";
    mysqli_query($conn, $delete_answers_query);

    // ลบคำถามที่เกี่ยวข้องกับแบบฝึกหัดนี้
    $delete_questions_query = "DELETE FROM exercise_questions WHERE exercise_id = '$exercise_id'";
    mysqli_query($conn, $delete_questions_query);

    // ลบแบบฝึกหัดจากฐานข้อมูล
    $delete_exercise_query = "DELETE FROM exercises WHERE id = '$exercise_id'";
    if (mysqli_query($conn, $delete_exercise_query)) {
        $_SESSION['success'] = "ลบแบบฝึกหัดสำเร็จ!";
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบแบบฝึกหัด!";
    }
} else {
    $_SESSION['error'] = "ไม่มีค่า exercise_id หรือ course_id ที่ถูกส่งมา";
}

// หลังจากลบเสร็จให้เปลี่ยนเส้นทางกลับไปยังหน้ารายละเอียดคอร์ส
header("Location: course_detail.php?course_id=" . $course_id);
exit();

?>
