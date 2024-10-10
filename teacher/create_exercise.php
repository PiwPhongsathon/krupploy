<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้เป็นครูหรือไม่
if ($_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// ดึงคอร์สทั้งหมดที่ผู้ใช้เป็นครูผู้สอน
$teacher_id = $_SESSION['userid'];
$courses_query = "
    SELECT c.*
    FROM courses c
    LEFT JOIN exercises e ON c.id = e.course_id
    WHERE c.user_id = '$teacher_id' AND e.course_id IS NULL
";
$courses_result = $conn->query($courses_query);

// ตรวจสอบว่ามีการส่งข้อมูลมาเพื่อสร้างแบบฝึกหัด
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $exercise_name = $_POST['exercise_name'];

    // ตรวจสอบว่าคอร์สนี้มีแบบฝึกหัดแล้วหรือไม่
    $check_exercise_query = "SELECT * FROM exercises WHERE course_id = '$course_id'";
    $check_exercise_result = $conn->query($check_exercise_query);

    if ($check_exercise_result->num_rows > 0) {
        // ถ้ามีแบบฝึกหัดอยู่แล้ว ไม่อนุญาตให้สร้างแบบฝึกหัดใหม่
        $_SESSION['error'] = "คอร์สนี้มีแบบฝึกหัดอยู่แล้ว!";
    } else {
        // เพิ่มแบบฝึกหัดในตาราง exercises
        $insert_exercise_query = "INSERT INTO exercises (course_id, exercise_name) VALUES ('$course_id', '$exercise_name')";
        if ($conn->query($insert_exercise_query) === TRUE) {
            $exercise_id = $conn->insert_id; // เก็บ ID ของแบบฝึกหัดใหม่ที่เพิ่งสร้าง

            // ตรวจสอบว่ามีการส่งคำถามและตัวเลือกมาและไม่เป็น null
            if (isset($_POST['questions']) && is_array($_POST['questions']) && isset($_POST['choices']) && is_array($_POST['choices'])) {
                // เพิ่มคำถามและตัวเลือกคำตอบที่ถูกส่งมาจากฟอร์ม
                foreach ($_POST['questions'] as $index => $question_text) {
                    // เพิ่มคำถามในตาราง exercise_questions
                    $insert_question_query = "INSERT INTO exercise_questions (exercise_id, question_text) VALUES ('$exercise_id', '$question_text')";
                    $conn->query($insert_question_query);
                    $question_id = $conn->insert_id; // เก็บ ID ของคำถามที่เพิ่งสร้าง

                    // ตรวจสอบว่ามีตัวเลือกคำตอบสำหรับคำถามนี้
                    if (isset($_POST['choices'][$index]) && is_array($_POST['choices'][$index])) {
                        // เพิ่มตัวเลือกคำตอบในตาราง exercise_choices
                        foreach ($_POST['choices'][$index] as $choice_index => $choice_text) {
                            $is_correct = ($_POST['correct_choice'][$index] == $choice_index) ? 1 : 0;
                            $insert_choice_query = "INSERT INTO exercise_choices (question_id, choice_text, is_correct) VALUES ('$question_id', '$choice_text', '$is_correct')";
                            $conn->query($insert_choice_query);
                        }
                    }
                }
            } else {
                $_SESSION['error'] = "ไม่มีคำถามหรือคำตอบถูกส่งมา";
            }

            $_SESSION['success'] = "แบบฝึกหัดถูกสร้างสำเร็จ!";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการสร้างแบบฝึกหัด: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/teacher.css">
    <title>สร้างแบบฝึกหัด</title>

</head>

<body>
    <?php include '../components/teacher_header.php'; ?>
    <div class="sidebar">
        <ul>
            <li><a href="program.php"><img src="icon-overview.svg" alt=""> สรุปรายการสินค้า</a></li>
            <li><a href="mycourse.php"><img src="icon-courses.svg" alt=""> คอร์สเรียนของฉัน</a></li>
            <li><a href="mystudent.php"><img src="icon-path.svg" alt=""> นักเรียนของฉัน</a></li>
            <li><a href="myteacher.php"><img src="icon-path.svg" alt=""> คุณครู</a></li>
        </ul>
    </div>
    <div class="container mt-5 form-wrapper">
        <h3 class="text-center mb-4">สร้างแบบฝึกหัดใหม่</h2>

            <form action="create_exercise.php" method="POST" class="needs-validation" novalidate>
                <!-- เลือกคอร์ส -->
                <div class="mb-3">
                    <label for="course_id" class="form-label">เลือกคอร์ส</label>
                    <select class="form-select" name="course_id" id="course_id" required>
                        <option value="">เลือกคอร์ส</option>
                        <?php while ($course = $courses_result->fetch_assoc()) { ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        กรุณาเลือกคอร์ส.
                    </div>
                </div>

                <!-- ชื่อแบบฝึกหัด -->
                <div class="mb-3">
                    <label for="exercise_name" class="form-label">ชื่อแบบฝึกหัด</label>
                    <input type="text" class="form-control" name="exercise_name" id="exercise_name" placeholder="กรอกชื่อแบบฝึกหัด" required>
                    <div class="invalid-feedback">
                        กรุณากรอกชื่อแบบฝึกหัด.
                    </div>
                </div>

                <!-- คำถามและตัวเลือกคำตอบ -->
                <div id="questions-container" class="mb-3">
                    <div class="question-block p-3 mb-3 border rounded shadow-sm">
                        <h5 class="mb-3">คำถาม 1</h5>
                        <input type="text" class="form-control mb-3" name="questions[]" placeholder="กรอกคำถาม" required>
                        <div class="invalid-feedback">
                            กรุณากรอกคำถาม.
                        </div>

                        <label class="form-label">ตัวเลือกคำตอบ</label>
                        <div class="choices">
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[0]" value="0" required>
                                </div>
                                <input type="text" class="form-control" name="choices[0][]" placeholder="ตัวเลือกที่ 1" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 1.
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[0]" value="1" required>
                                </div>
                                <input type="text" class="form-control" name="choices[0][]" placeholder="ตัวเลือกที่ 2" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 2.
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[0]" value="2" required>
                                </div>
                                <input type="text" class="form-control" name="choices[0][]" placeholder="ตัวเลือกที่ 3" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 3.
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[0]" value="3" required>
                                </div>
                                <input type="text" class="form-control" name="choices[0][]" placeholder="ตัวเลือกที่ 4" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 4.
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger mt-2 remove-question-btn">ลบคำถาม</button>
                        </div>
                    </div>
                </div>

                <!-- ปุ่มเพิ่มคำถาม -->
                <button type="button" id="add-question-btn" class="btn btn-outline-secondary mb-3">เพิ่มคำถาม</button>

                <!-- ปุ่มสร้างแบบฝึกหัด -->
                <button type="submit" class="btn btn-primary w-100">สร้างแบบฝึกหัด</button>
            </form>
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Validation
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()


        function updateQuestionNumbers() {
            const questionBlocks = document.querySelectorAll('.question-block');
            questionBlocks.forEach((block, index) => {
                const questionLabel = block.querySelector('h5');
                questionLabel.textContent = `คำถาม ${index + 1}`; // อัปเดตหมายเลขคำถามใหม่
            });
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-question-btn')) {
                const questionBlock = event.target.closest('.question-block');
                questionBlock.remove();
                updateQuestionNumbers(); // เรียกฟังก์ชันอัปเดตหมายเลขคำถามใหม่
            }
        });
    </script>

    <!-- สคริปต์สำหรับเพิ่มคำถาม -->
    <script>
        let questionIndex = 1;

        document.getElementById('add-question-btn').addEventListener('click', function() {
            const questionIndex = document.querySelectorAll('.question-block').length;

            const questionContainer = document.createElement('div');
            questionContainer.classList.add('question-block', 'mb-3');

            questionContainer.innerHTML = `
        <h5 class="mb-3">คำถาม ${questionIndex + 1}</h5>
        <input type="text" class="form-control mb-2" name="questions[]" required>

        <label class="form-label">ตัวเลือกคำตอบ</label>
        <div class="choices">
        <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[${questionIndex}]" value="0" required>
                                </div>
                                <input type="text" class="form-control" name="choices[${questionIndex}][]" placeholder="ตัวเลือกที่ 1" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 1.
                                </div>
                            </div>
        <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[${questionIndex}]" value="1" required>
                                </div>
                                <input type="text" class="form-control" name="choices[${questionIndex}][]" placeholder="ตัวเลือกที่ 2" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 2.
                                </div>
                            </div>
        <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[${questionIndex}]" value="2" required>
                                </div>
                                <input type="text" class="form-control" name="choices[${questionIndex}][]" placeholder="ตัวเลือกที่ 3" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 3.
                                </div>
                            </div>
        <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_choice[${questionIndex}]" value="3" required>
                                </div>
                                <input type="text" class="form-control" name="choices[${questionIndex}][]" placeholder="ตัวเลือกที่ 4" required>
                                <div class="invalid-feedback">
                                    กรุณากรอกตัวเลือกที่ 4.
                                </div>
                           
        </div>
        <button type="button" class="btn btn-danger delete-question-btn mt-2">ลบคำถาม</button>
    `;

            document.getElementById('questions-container').appendChild(questionContainer);
        });
    </script>

    <!-- SweetAlert -->
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: '<?php echo $_SESSION['success']; ?>',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'mycourse.php'; // หรือเปลี่ยนเส้นทางไปที่อื่น
            });
            <?php unset($_SESSION['success']); ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: '<?php echo $_SESSION['error']; ?>',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'mycourse.php'; // หรือเปลี่ยนเส้นทางไปที่อื่น
            });
            <?php unset($_SESSION['error']); ?>
        </script>
    <?php endif; ?>

</body>
<script src="../js/navbar.js"></script>
<script src="../js/dropdownmenu.js"></script>

</html>