<?php
session_start();
require_once "components/database.php";

$userId = $_SESSION['userid'];

// ตรวจสอบข้อมูลในตาราง teacher_profiles
$sql = "SELECT COUNT(*) as count FROM teacher_profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = ['exists' => $row['count'] > 0];

// ส่งผลลัพธ์กลับไปเป็น JSON
header('Content-Type: application/json');
echo json_encode($response);
?>