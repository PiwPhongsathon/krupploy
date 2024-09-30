<?php
require_once "../components/database.php";

date_default_timezone_set('Asia/Bangkok');


if (isset($_GET['id'])) {
    $purchase_id = $_GET['id'];
    $confirmation_date = date('Y-m-d H:i:s'); // วันที่และเวลาปัจจุบัน

    // คำนวณเวลาหมดอายุ (ตัวอย่างนี้คือ 10 นาทีหลังจากการยืนยัน)
    $expiry_date = date('Y-m-d H:i:s', strtotime($confirmation_date . ' +10 minutes'));

    // อัปเดตสถานะเป็น "ยืนยันแล้ว" พร้อมทั้งบันทึกวันที่และเวลา และเวลาหมดอายุ
    $query = "UPDATE course_purchases SET status = 'ยืนยันแล้ว', confirmation_date = '$confirmation_date', expiry_date = '$expiry_date' WHERE id = '$purchase_id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}

mysqli_close($conn);

?>
