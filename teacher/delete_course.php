<?php
session_start();
require_once "../components/database.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];


    // ดึงข้อมูลชื่อไฟล์รูปภาพและวิดีโอที่เกี่ยวข้องกับคอร์ส
    $query = "SELECT cover_image FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // ลบไฟล์รูปภาพใน covers
            $cover_path = '../uploads/covers/' . $row['cover_image'];
            if (file_exists($cover_path)) {
                unlink($cover_path);
            }

        }
    }

    // ตรวจสอบว่าคอร์สนี้มีอยู่ในฐานข้อมูลหรือไม่
    $query = "DELETE FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // ลบสำเร็จ
        header("Location: mycourse.php");
        exit();
    } else {
        // ลบไม่สำเร็จ
        echo "Error deleting course: " . mysqli_error($conn);
    }
} else {
    echo "No course ID provided";
}
?>
