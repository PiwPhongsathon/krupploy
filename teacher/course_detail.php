<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

// ตรวจสอบว่ามีการส่งค่า course_id มา
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // ดึงข้อมูลคอร์สจากฐานข้อมูล
    $query = "SELECT * FROM courses WHERE id = '$course_id'";
    $result = mysqli_query($conn, $query);
    $lessons_query = "SELECT * FROM lessons WHERE course_id = '$course_id'";
    $lessons_result = mysqli_query($conn, $lessons_query);

    // ตรวจสอบว่าพบคอร์สหรือไม่
    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "ไม่พบคอร์สที่เลือก";
        exit();
    }

    // ดึงข้อมูลนักเรียนที่ลงทะเบียนในคอร์สนี้
    $students_query = "SELECT cp.id, CONCAT(u.fname, ' ', u.lname) AS username, u.email FROM user u 
                       INNER JOIN course_purchases cp ON u.id = cp.student_id 
                       WHERE cp.course_id = '$course_id'";
    $students_result = mysqli_query($conn, $students_query);

    // ดึงข้อมูลแบบฝึกหัดที่สร้างในคอร์สนี้
    $exercises_query = "SELECT * FROM exercises WHERE course_id = '$course_id'";
    $exercises_result = mysqli_query($conn, $exercises_query);
} else {
    echo "ไม่มีการส่งค่า course_id มา";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคอร์ส - KruPPloy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap JS (รวม Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="../css/teacher.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">


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

    <div class="containner_program">
        <div class="main-content">
            <h2>รายละเอียดคอร์ส</h2>

            <section class="watch-video">
                <div class="video-container">
                    <div class="image">
                        <img src="../uploads/covers/<?php echo $course['cover_image']; ?>" alt="course image" class="course-cover-image">
                    </div>

                </div>

                <div class="course-details1">
                    <h4 class="course-title1"><?php echo $course['course_name']; ?></h4>
                    <p class="course-subject1"><strong>วิชา: </strong><?php echo $course['subject']; ?></p>
                    <p class="course-description1"><strong>รายละเอียด:</strong><?php echo $course['course_content']; ?></p>
                    <p class="course-description1"><strong>ราคา:</strong> <span class="text-danger"><?= number_format($course['price'], 2); ?> บาท</span></p>
                </div>
            </section>
            
            <div class="lessons-dropdown">
                <h4>บทเรียนในคอร์สนี้</h4>
                <?php if (mysqli_num_rows($lessons_result) > 0): ?>
                    <div class="accordion" id="lessonsAccordion">
                        <?php while ($lesson = mysqli_fetch_assoc($lessons_result)): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $lesson['id']; ?>">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $lesson['id']; ?>" aria-expanded="true" aria-controls="collapse<?= $lesson['id']; ?>">
                                        <?= $lesson['lesson_title']; ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $lesson['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $lesson['id']; ?>" data-bs-parent="#lessonsAccordion">
                                    <div class="accordion-body">
                                        <?= $lesson['lesson_content']; ?>
                                        <a href="watch_video.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson['id']; ?>" class="view-video-button">
                                            <button>เริ่มเรียน</button>
                                        </a>
                                    </div>


                                </div>

                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>ยังไม่มีบทเรียนในคอร์สนี้</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h4>นักเรียนที่ลงทะเบียนเรียนในคอร์สนี้</h4>
                </div>
                <?php if (mysqli_num_rows($students_result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <td>ชื่อนักเรียน</td>
                                <td>อีเมล</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                <tr>
                                    <td><?= $student['username']; ?></td>
                                    <td><?= $student['email']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>ยังไม่มีนักเรียนลงทะเบียนในคอร์สนี้</p>
                <?php endif; ?>
            </div>
            <div class="recentOrders">
                <div class="cardHeader">
                    <h4>แบบฝึกหัดในคอร์สนี้</h4>
                </div>

                <?php if (mysqli_num_rows($exercises_result) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <td>ชื่อแบบฝึกหัด</td>
                                <td>สร้างเมื่อวันที่</td>
                                <td>การจัดการ</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($exercise = mysqli_fetch_assoc($exercises_result)): ?>
                                <tr>
                                    <td><?= $exercise['exercise_name']; ?></td>
                                    <td><?= $exercise['created_at']; ?></td>
                                    <td>
                                        <form action="delete_exercise.php" method="POST" onsubmit="return confirmDelete();">
                                            <input type="hidden" name="exercise_id" value="<?= $exercise['id']; ?>">
                                            <input type="hidden" name="course_id" value="<?= $course_id; ?>">
                                            <button class="btn btn-danger delete-exercise-btn" data-exercise-id="<?= $exercise['id']; ?>" data-course-id="<?= $course_id; ?>">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>ยังไม่มีการสร้างแบบฝึกหัดในคอร์สนี้</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-exercise-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // ป้องกันการส่งฟอร์มอัตโนมัติ

                    const exerciseId = this.getAttribute('data-exercise-id'); // ดึงค่า exercise_id จากปุ่ม
                    const courseId = this.getAttribute('data-course-id'); // ดึงค่า course_id จากปุ่ม

                    // ตรวจสอบค่า exerciseId และ courseId ก่อนส่งไป PHP
                    console.log("Exercise ID:", exerciseId);
                    console.log("Course ID:", courseId);

                    // SweetAlert2 สำหรับยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจว่าต้องการลบแบบฝึกหัดนี้หรือไม่?',
                        text: "การลบแบบฝึกหัดจะไม่สามารถกู้คืนได้!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ส่งค่า exercise_id และ course_id ด้วยวิธี POST ไปยัง delete_exercise.php
                            fetch('delete_exercise.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: 'exercise_id=' + exerciseId + '&course_id=' + courseId // ส่งค่า exercise_id และ course_id
                                })
                                .then(response => response.text())
                                .then(data => {
                                    console.log(data); // ตรวจสอบข้อมูลที่ได้รับจาก PHP

                                    Swal.fire(
                                        'ลบเรียบร้อย!',
                                        'แบบฝึกหัดของคุณถูกลบแล้ว.',
                                        'success'
                                    ).then(() => {
                                        // เปลี่ยนเส้นทางกลับไปยังหน้ารายละเอียดคอร์ส
                                        window.location.href = 'course_detail.php?course_id=' + courseId;
                                    });
                                })
                                .catch(error => {
                                    console.log("Error:", error); // ตรวจสอบข้อผิดพลาด
                                    Swal.fire(
                                        'เกิดข้อผิดพลาด!',
                                        'ไม่สามารถลบแบบฝึกหัดได้.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: '<?php echo $_SESSION['success']; ?>',
                icon: 'success',
                confirmButtonText: 'ตกลง'
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
            });
            <?php unset($_SESSION['error']); ?>
        </script>
    <?php endif; ?>
</body>
<script src="../js/navbar.js"></script>
<script src="../js/dropdownmenu.js"></script>

</html>