<?php
require_once "database.php";

if (isset($_GET['id'])) {
    
    $purchase_id = $_GET['id'];


    $query = "SELECT slip_image FROM course_purchases WHERE id = '$purchase_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // ลบไฟล์รูปภาพใน covers
            $cover_path = '../uploads/slips/' . $row['slip_image'];
            if (file_exists($cover_path)) {
                unlink($cover_path);
            }

        }
    }
    
    $query = "DELETE FROM course_purchases WHERE id = '$purchase_id'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
