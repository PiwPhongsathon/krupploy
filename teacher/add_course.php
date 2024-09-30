<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

// ตรวจสอบว่าผู้ใช้ได้อัปโหลดสลิปธนาคารแล้วหรือยัง
$query = "SELECT bank_slip_image FROM user WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (empty($row['bank_slip_image'])) {
    $_SESSION['error'] = "กรุณาอัปโหลดสลิปธนาคารก่อนเพิ่มคอร์ส <br> (เพิ่มสลิปได้ที่เมนู ตั้งค่า)";
}

if (isset($_POST['submit'])) {
    if (empty($row['bank_slip_image'])) {
        $_SESSION['error'] = "กรุณาอัปโหลดสลิปธนาคารก่อนเพิ่มคอร์ส <br> (เพิ่มสลิปได้ที่เมนู ตั้งค่า)";
    } else {
        $course_name = filter_var($_POST['course_name'], FILTER_SANITIZE_STRING);
        $course_content = filter_var($_POST['course_content'], FILTER_SANITIZE_STRING);
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // รับลิงก์วิดีโอ
        $video_link = filter_var($_POST['video_link'], FILTER_SANITIZE_URL);

        // ตรวจสอบและแปลงลิงก์ Google Drive จาก /view?usp=sharing เป็น /preview
        if (strpos($video_link, '/view?usp=sharing') !== false) {
            $video_link = str_replace('/view?usp=sharing', '/preview', $video_link);
        }

        // Handling file upload for the cover image
        $cover_image = $_FILES['cover_image']['name'];
        $cover_image = filter_var($cover_image, FILTER_SANITIZE_STRING);
        $cover_ext = pathinfo($cover_image, PATHINFO_EXTENSION);
        $rename_cover = uniqid() . '.' . $cover_ext;
        $cover_tmp_name = $_FILES['cover_image']['tmp_name'];
        $cover_folder = '../uploads/covers/' . $rename_cover;

        if (move_uploaded_file($cover_tmp_name, $cover_folder)) {
            // บันทึกข้อมูลลงฐานข้อมูล
            $query = "INSERT INTO courses (cover_image, video_link, course_name, course_content, subject, price, user_id) 
                      VALUES ('$rename_cover', '$video_link', '$course_name', '$course_content', '$subject', '$price', '$user_id')";

            $result = mysqli_query($conn, $query);

            if ($result) {
                $_SESSION['success'] = "เพิ่มคอร์สสำเร็จ";
            } else {
                $_SESSION['error'] = "Something went wrong: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "File upload failed";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/teacher.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="container my-5">
        <section class="video-form">
            <div class="">
                <h3 class="text-center mb-4">เพิ่มคอร์ส</h3>
                <form action="" method="post" enctype="multipart/form-data" id="courseForm" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">ชื่อคอร์ส <span class="text-danger">*</span></label>
                        <input type="text" name="course_name" id="course_name" maxlength="100" required placeholder="กรอกชื่อคอร์ส" class="form-control">
                        <div class="invalid-feedback">กรุณากรอกชื่อคอร์ส.</div>
                    </div>
                    <div class="mb-3">
                        <label for="course_content" class="form-label">เนื้อหาคอร์ส <span class="text-danger">*</span></label>
                        <textarea name="course_content" id="course_content" class="form-control" required placeholder="เขียนเนื้อหาคอร์ส" maxlength="1000" rows="5"></textarea>
                        <div class="invalid-feedback">กรุณากรอกเนื้อหาคอร์ส.</div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">ราคา <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price" maxlength="100" required placeholder="กรอกราคาคอร์ส" class="form-control">
                        <div class="invalid-feedback">กรุณากรอกราคา.</div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">หมวดหมู่รายวิชา <span class="text-danger">*</span></label>
                        <select name="subject" id="subject" class="form-select" required>
                            <option value="" disabled selected>เลือกหมวดหมู่</option>
                            <option value="ภาษาอังกฤษ">ภาษาอังกฤษ</option>
                            <option value="ภาษาไทย">ภาษาไทย</option>
                            <option value="คณิตศาสตร์">คณิตศาสตร์</option>
                        </select>
                        <div class="invalid-feedback">กรุณาเลือกหมวดหมู่รายวิชา.</div>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">ภาพปกคลิป <span class="text-danger">*</span></label>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*" required class="form-control">
                        <div class="invalid-feedback">กรุณาอัปโหลดภาพปกคลิป.</div>
                    </div>
                    <div class="mb-4">
                        <label for="video_link" class="form-label">ลิงก์คลิปวีดีโอ <span class="text-danger">*</span></label>
                        <input type="url" name="video_link" id="video_link" required placeholder="ใส่ลิงก์วิดีโอจาก Google Drive หรือ Dropbox" class="form-control">
                        <div class="invalid-feedback">กรุณาใส่ลิงก์วิดีโอ.</div>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="submit" id="addCourseBtn" class="btn btn-primary">เพิ่มคอร์ส</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Validation
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
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
    </script>

    <script src="../js/navbar.js"></script>
    <script src="../js/dropdownmenu.js"></script>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                title: 'เพิ่มคอร์สสำเร็จ!',
                text: '<?php echo addslashes($_SESSION['success']); ?>',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'mycourse.php';
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                html: '<?php echo addslashes($_SESSION['error']); ?>',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'mycourse.php'; // หรือเปลี่ยนเส้นทางไปที่อื่น
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</body>

</html>