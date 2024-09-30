<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่าผู้ใช้เป็นนักเรียนหรือไม่
if ($_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ามีการส่งแบบฝึกหัดที่ต้องการทำ
$exercise_id = isset($_GET['exercise_id']) ? $_GET['exercise_id'] : null;

if ($exercise_id) {
    // ดึงข้อมูลแบบฝึกหัด
    $exercise_query = "SELECT * FROM exercises WHERE id = '$exercise_id'";
    $exercise_result = $conn->query($exercise_query);

    // ตรวจสอบว่ามีแบบฝึกหัดนี้หรือไม่
    if (!$exercise_result || $exercise_result->num_rows == 0) {
        echo "ไม่พบแบบฝึกหัดที่ต้องการทำ";
        exit();
    }

    $exercise = $exercise_result->fetch_assoc();

    // ดึงคำถามทั้งหมดในแบบฝึกหัด
    $questions_query = "SELECT * FROM exercise_questions WHERE exercise_id = '$exercise_id'";
    $questions_result = $conn->query($questions_query);
} else {
    echo "ไม่พบแบบฝึกหัดที่ต้องการทำ";
    exit();
}

// ตรวจสอบการส่งคำตอบของนักเรียน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['userid'];
    $total_score = 0;
    $max_score = count($_POST['answers']);  // จำนวนคำถามทั้งหมด

    // ตรวจสอบคำตอบและคำนวณคะแนน
    foreach ($_POST['answers'] as $question_id => $selected_choice_id) {
        $choice_query = "SELECT is_correct FROM exercise_choices WHERE id = '$selected_choice_id'";
        $choice_result = $conn->query($choice_query);
        $choice = $choice_result->fetch_assoc();

        if ($choice['is_correct']) {
            $total_score++;  // เพิ่มคะแนนถ้าตอบถูก
        }
    }

    // บันทึกผลลัพธ์ลงในตาราง exercise_results
    $insert_result_query = "INSERT INTO exercise_results (user_id, exercise_id, score) VALUES ('$user_id', '$exercise_id', '$total_score')";
    $conn->query($insert_result_query);

    // ใช้ JavaScript เพื่อเรียก SweetAlert และทำการ Redirect
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'ทำแบบฝึกหัดเสร็จแล้ว!',
            text: 'คุณทำได้ $total_score/$max_score คะแนน',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'course_detail.php?course_id=" . $exercise['course_id'] . "';
            }
        });
    });
</script>
    ";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำแบบฝึกหัด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Noto Sans Thai";
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            background-image: url('imagehappy2.png');
            /* ใส่ path รูปภาพ */
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            position: relative;
        }

        h2 {
            font-size: 28px;
            color: #6C63FF;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        h5 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
        }

        .form-check {
            margin-bottom: 15px;
        }

        .form-check-input {
            margin-right: 10px;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 18px;
            color: #555;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #6C63FF;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            margin-top: 30px;
        }

        .btn-primary:hover {
            background-color: #574fbf;
        }

        /* ปุ่มย้อนกลับ */
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #ff6b6b;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            z-index: 9999;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background-color: #ff4c4c;
        }

        /* เพิ่มไอคอนเด็กๆ */
        .back-button::before {
            content: "⬅";
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 22px;
            }

            h5 {
                font-size: 16px;
            }

            .btn-primary {
                font-size: 16px;
                padding: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- ปุ่มย้อนกลับ -->
    <button class="back-button" onclick="goBack()">ย้อนกลับ</button>

    <div class="container">
        <h2>ทำแบบฝึกหัด: <?php echo $exercise['exercise_name']; ?></h2>

        <form action="take_exercise.php?exercise_id=<?php echo $exercise_id; ?>&course_id=<?php echo isset($_GET['course_id']) ? $_GET['course_id'] : ''; ?>" method="POST">

            <?php
            $question_number = 1;
            while ($question = $questions_result->fetch_assoc()) {
                $question_id = $question['id'];
                // ดึงตัวเลือกคำตอบสำหรับคำถามนี้
                $choices_query = "SELECT * FROM exercise_choices WHERE question_id = '$question_id'";
                $choices_result = $conn->query($choices_query);
            ?>
                <div class="mb-3">
                    <h5><?php echo $question_number . ". " . $question['question_text']; ?></h5>

                    <?php while ($choice = $choices_result->fetch_assoc()) { ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $choice['id']; ?>" required>
                            <label class="form-check-label">
                                <?php echo $choice['choice_text']; ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            <?php
                $question_number++;
            }
            ?>

            <button type="submit" class="btn-primary">ส่งคำตอบ</button>
        </form>
    </div>


    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    
</body>

</html>