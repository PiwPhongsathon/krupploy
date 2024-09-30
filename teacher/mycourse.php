<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

$user_id = $_SESSION['userid'];

$query = "SELECT bank_slip_image FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
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
            <div class="header">
                <h2>คอร์สเรียนของฉัน</h2>
                <div class="btn-group">
                    <a href="add_course.php" class="btn-addcourse">
                        <i class="fas fa-plus"></i> เพิ่มคอร์ส
                    </a>
                    <a href="create_exercise.php" class="btn-addexercise">
                        <i class="fas fa-edit"></i> เพิ่มแบบฝึกหัด
                    </a>
                </div>
            </div>

            <section class="dashboard-content">
                <?php
                $user_id = $_SESSION['userid'];
                $query = "SELECT * FROM courses WHERE user_id = '$user_id'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($course = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="boxcourse">
                            <img src="../uploads/covers/<?= $course['cover_image']; ?>" alt="course" class="course-image">
                            <div class="textboxcourse">
                                <p class="course-subject"><?= $course['subject']; ?></p>
                                <h4 class="course-title"><?= $course['course_name']; ?></h4>
                                <p class="course-content"><?= $course['course_content']; ?></p>
                                <div class="flex-btn">
                                    <a href="edit_course.php?id=<?= $course['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> แก้ไข
                                    </a>
                                    <a href="javascript:void(0);" class="btn-remove" onclick="confirmDelete('<?= $course['id']; ?>')">
                                        <i class="fas fa-trash-alt"></i> ลบ
                                    </a>
                                </div>
                                <a href="course_detail.php?course_id=<?= $course['id']; ?>" class="btn-infocourse">ดูรายละเอียดคอร์ส</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">ยังไม่มีคอร์สที่เพิ่มไว้!</p>';
                }
                ?>

                <script>
                    function confirmDelete(courseId) {
                        Swal.fire({
                            title: 'คุณแน่ใจหรือไม่?',
                            text: "การกระทำนี้ไม่สามารถย้อนกลับได้!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่, ลบเลย!',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `delete_course.php?id=${courseId}`;
                            }
                        })
                    }
                </script>
            </section>
        </div>
    </div>

    <script src="../js/navbar.js"></script>
    <script src="../js/dropdownmenu.js"></script>
</body>

</html>