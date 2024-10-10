<?php
session_start();
require_once "components/database.php";
date_default_timezone_set('Asia/Bangkok');

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['userid']; // อ้างอิงจากนักเรียนที่เข้าสู่ระบบ

// ดึงข้อมูลคอร์สที่นักเรียนคนนี้ซื้อแล้วและยืนยันแล้ว
$query = "SELECT c.id, c.course_name, c.subject, c.cover_image, c.course_content, cp.confirmation_date, cp.expiry_date 
          FROM course_purchases cp
          JOIN courses c ON cp.course_id = c.id
          WHERE cp.student_id = '$student_id' AND cp.status = 'ยืนยันแล้ว'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include './components/user_header.php'; ?>

    <div class="sidebar">
        <ul>
            <li><a href="program.php"><img src="icon-courses.svg" alt=""> คอร์สเรียนของฉัน</a></li>
            <li><a href="mycourse_wait.php"><img src="icon-path.svg" alt=""> คอร์สเรียนที่ลงทะเบียนแล้ว</a></li>
        </ul>
    </div>

    <div class="containner_program">
        <div class="main-content">
            <h2>คอร์สเรียนของฉัน</h2>
            <section class="dashboard-content">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // คำนวณเวลาหมดอายุ
                        $confirmation_date = new DateTime($row['confirmation_date']);
                        $expiry_time = clone $confirmation_date; // ทำสำเนาเพื่อไม่ให้แก้ไขวันที่ดั้งเดิม
                        $expiry_time->modify('+365 days'); // ปรับตามเวลาที่ต้องการ
                        $current_time = new DateTime();

                        // คำนวณเวลาที่เหลือในวินาที
                        $time_left = $expiry_time->getTimestamp() - $current_time->getTimestamp();

                        if ($time_left > 0) {
                            // ถ้าเวลายังเหลือให้แสดงข้อมูลคอร์สพร้อมลิงก์เข้าไปดูรายละเอียดได้
                ?>
                            <a href="course_detail.php?course_id=<?= $row['id']; ?>">
                                <div class="boxcourse">
                                    <img src="uploads/covers/<?= $row['cover_image']; ?>" alt="course" class="course-image">
                                    <div class="textboxcourse">
                                        <p class="course-subject">วิชา: <?= $row['subject']; ?></p>
                                        <h5 class="course-title"><?= $row['course_name']; ?></h5>
                                        <p class="course-content"><?= $row['course_content']; ?></p>
                                        <p><strong>เวลาที่เหลือ:</strong> <span id="timer-<?= $row['id']; ?>"></span></p>
                                    </div>
                                </div>
                                <script>
                                    var countDownDate_<?= $row['id']; ?> = new Date('<?= $expiry_time->format('Y-m-d H:i:s'); ?>').getTime();
                                    var x_<?= $row['id']; ?> = setInterval(function() {
                                        var now = new Date().getTime();
                                        var distance = countDownDate_<?= $row['id']; ?> - now;

                                        // คำนวณวัน ชั่วโมง นาที และ วินาที
                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        // แสดงผล "วัน ชั่วโมง นาที วินาที"
                                        document.getElementById('timer-<?= $row['id']; ?>').innerHTML = days + ' วัน ' + hours + ' ชั่วโมง ' + minutes + ' นาที ' + seconds + ' วินาที ';

                                        // ถ้าเวลาหมดแล้ว
                                        if (distance < 0) {
                                            clearInterval(x_<?= $row['id']; ?>);
                                            document.getElementById('timer-<?= $row['id']; ?>').innerHTML = 'หมดเวลาการใช้งาน';
                                        }
                                    }, 1000);
                                </script>
                            </a>
                        <?php

                        } else {
                            // ถ้าเวลาหมดแล้วให้แสดงข้อความคอร์สหมดอายุ
                        ?>
                            <a href="javascript:void(0)" onclick="courseExpired()">
                                <div class="boxcourse">

                                    <img src="uploads/covers/<?= $row['cover_image']; ?>" alt="course" class="course-image">
                                    <div class="textboxcourse">
                                        <p class="course-subject">วิชา: <?= $row['subject']; ?></p>
                                        <h5 class="course-title"><?= $row['course_name']; ?></h5>
                                        <p class="course-content"><?= $row['course_content']; ?></p>
                                        <p style="color:red;"><strong>หมดเวลาการใช้งานแล้ว</strong></p>
                                    </div>

                                </div>
                            </a>
                <?php
                        }
                    }
                } else {
                    echo '<p class="empty">ยังไม่มีคอร์สที่ยืนยันแล้ว!</p>';
                }
                ?>
            </section>
        </div>
    </div>

    <script src="navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>
    <script>
        function courseExpired() {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถดูได้',
                text: 'คอร์สนี้หมดอายุแล้ว!',
                confirmButtonText: 'ตกลง'
            });
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>