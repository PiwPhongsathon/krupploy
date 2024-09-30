<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่ามีการส่ง course_id มาหรือไม่
if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];

    // ดึงข้อมูลคอร์สจากฐานข้อมูลโดยใช้ course_id
    $sql = "SELECT * FROM courses WHERE id = $courseId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        // ดึงข้อมูล bank slip image
        $teacher_id = $course['user_id'];
        $teacher_query = "SELECT fname, lname, profile_pic,bank_slip_image FROM user WHERE id = '$teacher_id'";
        $teacher_result = mysqli_query($conn, $teacher_query);
        $teacher = mysqli_fetch_assoc($teacher_result);
        $teacher_name = $teacher['fname'] . ' ' . $teacher['lname'];
        $bank_slip_image = $teacher['bank_slip_image'];
        $profile_image = $teacher['profile_pic']; // ใช้รูปโปรไฟล์
    } else {
        echo "ไม่พบข้อมูลคอร์ส";
        exit;
    }
} else {
    echo "ไม่มีคอร์สที่เลือก";
    exit;
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert -->
    <style>
        .back-button {
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #ff4c4c;
        }

        .buy-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buy-button:hover {
            background-color: #218838;
        }

        .modal-body img {
            width: 100%;
        }

        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .teacher-image-container {
            margin-top: 20px;
            text-align: center;
        }

        .teacher-image-small {
            width: 35px;
            height: auto;
            border-radius: 50%;
            vertical-align: middle;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="burger" id="myburger" onclick="myFunctions()">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        <div class="navheader">
            <div class="logo">
                KruPPloy
            </div>
            <div>
                <ul class="nav-links" id="mynav-links">
                    <li class="text-white"><a href="index.php">หน้าหลัก</a></li>
                    <li><a href="course.php">คอร์สเรียน</a></li>
                    <li><a href="#">โปรแกรมการสอน</a></li>
                    <li><a href="index.php">คุณครู</a></li>
                </ul>
            </div>
            <a href="index.php">
                <div class="signup_button">
                    เข้าสู่ระบบ/สมัครสมาชิก
                </div>
            </a>
        </div>
    </nav>

    <!-- ปุ่มย้อนกลับ -->
    <div class="container mt-4">
        <button class="back-button" onclick="goBack()">ย้อนกลับ</button>
    </div>

    <div class="containner_program">
        <div class="main-content">
            <section class="watch-video">
                <div class="video-container">
                    <div class="image">
                        <img src="uploads/covers/<?php echo $course['cover_image']; ?>" alt="course image" class="course-cover-image">
                    </div>
                </div>

                <div class="course-details1">
                    <h4 class="course-title1"><?php echo $course['course_name']; ?></h4>
                    <p class="course-subject1">วิชา: <?php echo $course['subject']; ?></p>
                    <div class="course-content1">
                        <p>สอนโดย: <img src="uploads/profiles/<?php echo $profile_image; ?>" alt="Teacher Profile Image" class="teacher-image-small" /> <?php echo $teacher_name; ?></p>
                    </div>

                    <p class="course-description1"><?php echo $course['course_content']; ?></p>
                    <h4 class="price_course1"><?php echo $course['price']; ?> บาท</h4>

                    <!-- ปุ่มซื้อคอร์ส -->
                    <button type="button" class="btn btn-primary" onclick="handlePurchase()">ซื้อคอร์สนี้</button>
                </div>
            </section>
        </div>

        <!-- Modal สำหรับการซื้อคอร์ส -->
        <div class="modal fade" id="buyCourseModal" tabindex="-1" aria-labelledby="buyCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buyCourseModalLabel">การชำระเงินสำหรับคอร์ส: <?php echo $course['course_name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>กรุณาชำระเงินจาก QR CODE ด้านล่างนี้</p>
                        <img src="" id="modal-bank-slip" alt="Bank Slip Image" />

                        <p>หลังจากทำการโอนเงินแล้ว โปรดแนบสลิปเพื่อยืนยันการซื้อคอร์ส แล้วรอคุณครูตรวจสอบ</p>

                        <!-- ฟอร์มอัปโหลดสลิป -->
                        <form action="components/submit_slip.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="slipImage" class="form-label">แนบสลิปการโอนเงิน</label>
                                <input class="form-control" type="file" name="slip_image" id="slipImage" required>
                            </div>
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="btn btn-success">ยืนยันการโอนเงิน</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function goBack() {
                window.history.back();
            }

            // ฟังก์ชันแสดง SweetAlert ถ้าผู้ใช้ไม่ได้เข้าสู่ระบบ
            function handlePurchase() {
                <?php if (!isset($_SESSION['userid'])) { ?>
                    Swal.fire({
                        title: 'ยังไม่ได้เข้าสู่ระบบ',
                        text: 'กรุณาเข้าสู่ระบบก่อนซื้อคอร์สนี้!',
                        icon: 'warning',
                        confirmButtonText: 'ตกลง'
                    });
                <?php } else { ?>
                    // ถ้าผู้ใช้ล็อกอินแล้ว ให้เปิด Modal
                    var modal = new bootstrap.Modal(document.getElementById('buyCourseModal'));
                    modal.show();
                <?php } ?>
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
