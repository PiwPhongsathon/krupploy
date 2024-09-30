<?php
session_start();
require_once "database.php";

// ดึง ID ของคอร์สจาก URL
$course_id = $_GET['course_id'];

// ดึงข้อมูลแบบฝึกหัดที่เชื่อมโยงกับคอร์ส
$exercise_query = "SELECT * FROM exercises WHERE course_id = '$course_id'";
$exercise_result = $conn->query($exercise_query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฝึกหัด</title>
</head>
<body>

    <h1>แบบฝึกหัดของคอร์ส</h1>

    <ul>
        <?php while ($exercise = $exercise_result->fetch_assoc()) { ?>
            <li>
                <a href="do_exercise.php?exercise_id=<?php echo $exercise['id']; ?>">
                    <?php echo $exercise['exercise_name']; ?>
                </a>
            </li>
        <?php } ?>
    </ul>

</body>
</html>

<?php
$conn->close();
?>
