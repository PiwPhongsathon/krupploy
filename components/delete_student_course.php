<?php
require_once "../components/database.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // ลบข้อมูลในฐานข้อมูล
    $query = "DELETE FROM course_purchases WHERE id = '$course_id'";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting course']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}

mysqli_close($conn);
?>
