<?php
session_start();
require_once "database.php";

// ดึงข้อมูลจากฟอร์ม
$exercise_id = $_POST['exercise_id'];
$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'];

$score = 0;
$total_questions = count($answers);

// ตรวจสอบคำตอบที่ถูกต้อง
foreach ($answers as $question_id => $choice_id) {
    // ตรวจสอบว่าตัวเลือกที่เลือกถูกต้องหรือไม่
    $check_query = "SELECT is_correct FROM exercise_choices WHERE id = '$choice_id'";
    $check_result = $conn->query($check_query);
    $choice = $check_result->fetch_assoc();

    if ($choice['is_correct']) {
        $score++;
    }
}

// คำนวณคะแนน
$final_score = ($score / $total_questions) * 100;

// บันทึกผลลัพธ์ลงในตาราง exercise_results
$sql = "INSERT INTO exercise_results (user_id, exercise_id, score) VALUES ('$user_id', '$exercise_id', '$final_score')";
$conn->query($sql);

// แสดงผลคะแนน
echo "คุณทำคะแนนได้: $final_score %";

$conn->close();
?>
