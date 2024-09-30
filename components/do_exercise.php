<?php
session_start();
require_once "database.php";

// ดึง ID ของแบบฝึกหัดจาก URL
$exercise_id = $_GET['exercise_id'];

// ดึงข้อมูลคำถามในแบบฝึกหัด
$questions_query = "SELECT * FROM exercise_questions WHERE exercise_id = '$exercise_id'";
$questions_result = $conn->query($questions_query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำแบบฝึกหัด</title>
</head>
<body>

    <h1>ทำแบบฝึกหัด</h1>

    <form action="submit_exercise.php" method="POST">
        <input type="hidden" name="exercise_id" value="<?php echo $exercise_id; ?>">

        <?php while ($question = $questions_result->fetch_assoc()) { ?>
            <div>
                <p><?php echo $question['question_text']; ?></p>

                <?php
                // ดึงตัวเลือกของคำถาม
                $question_id = $question['id'];
                $choices_query = "SELECT * FROM exercise_choices WHERE question_id = '$question_id'";
                $choices_result = $conn->query($choices_query);
                ?>

                <?php while ($choice = $choices_result->fetch_assoc()) { ?>
                    <label>
                        <input type="radio" name="answers[<?php echo $question_id; ?>]" value="<?php echo $choice['id']; ?>">
                        <?php echo $choice['choice_text']; ?>
                    </label><br>
                <?php } ?>
            </div>
        <?php } ?>

        <button type="submit">ส่งคำตอบ</button>
    </form>

</body>
</html>

<?php
$conn->close();
?>
