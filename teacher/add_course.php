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

      
        // Handling file upload for the cover image
        $cover_image = $_FILES['cover_image']['name'];
        $cover_image = filter_var($cover_image, FILTER_SANITIZE_STRING);
        $cover_ext = pathinfo($cover_image, PATHINFO_EXTENSION);
        $rename_cover = uniqid() . '.' . $cover_ext;
        $cover_tmp_name = $_FILES['cover_image']['tmp_name'];
        $cover_folder = '../uploads/covers/' . $rename_cover;

        if (move_uploaded_file($cover_tmp_name, $cover_folder)) {
            // บันทึกข้อมูลลงฐานข้อมูล
            $query = "INSERT INTO courses (cover_image, course_name, course_content, subject, price, user_id) 
                      VALUES ('$rename_cover', '$course_name', '$course_content', '$subject', '$price', '$user_id')";

            $result = mysqli_query($conn, $query);

            if ($result) {
                $_SESSION['success'] = "เพิ่มคอร์สสำเร็จ";
            } else {
                $_SESSION['error'] = "Something went wrong: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "File upload failed";
        }
        if ($result) {
            // ดึง course_id ที่เพิ่งบันทึกเสร็จ
            $course_id = mysqli_insert_id($conn); 
        
            // ลูปสำหรับบันทึกบทเรียนทั้งหมด
            foreach ($_POST['lesson_title'] as $index => $lesson_title) {
                // ทำความสะอาดข้อมูลของบทเรียนแต่ละบท
                $lesson_title = filter_var($lesson_title, FILTER_SANITIZE_STRING);
                $lesson_content = filter_var($_POST['lesson_content'][$index], FILTER_SANITIZE_STRING);
                $lesson_video = filter_var($_POST['lesson_video'][$index], FILTER_SANITIZE_URL);
        
                // ตรวจสอบและแปลงลิงก์ Google Drive จาก /view?usp=sharing เป็น /preview
                if (strpos($lesson_video, '/view?usp=sharing') !== false) {
                    $lesson_video = str_replace('/view?usp=sharing', '/preview', $lesson_video);
                }
        
                // ตรวจสอบว่าได้ใส่ชื่อบทเรียนหรือไม่
                if (!empty($lesson_title)) {
                    // เพิ่มบทเรียนลงในตาราง lessons
                    $lesson_query = "INSERT INTO lessons (course_id, lesson_title, lesson_content, video_link)
                                     VALUES ('$course_id', '$lesson_title', '$lesson_content', '$lesson_video')";
                    mysqli_query($conn, $lesson_query);
                }
            }
        
            // ส่งข้อความสำเร็จกลับไปที่ session
            $_SESSION['success'] = "เพิ่มคอร์สและบทเรียนสำเร็จ";
        } else {
            $_SESSION['error'] = "Something went wrong: " . mysqli_error($conn);
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
                    <!-- <div class="mb-4">
                        <label for="video_link" class="form-label">ลิงก์คลิปวีดีโอ <span class="text-danger">*</span></label>
                        <input type="url" name="video_link" id="video_link" required placeholder="ใส่ลิงก์วิดีโอจาก Google Drive หรือ Dropbox" class="form-control">
                        <div class="invalid-feedback">กรุณาใส่ลิงก์วิดีโอ.</div>
                    </div> -->

                    <div id="lesson-blocks">
                        <div class="lesson-block mb-3">
                            <label for="lesson_title_1" class="form-label">ชื่อบทเรียนที่ 1<span class="text-danger">*</span></label>
                            <input type="text" name="lesson_title[]" id="lesson_title_1" maxlength="100" required placeholder="กรอกชื่อบทเรียน" class="form-control">
                            <div class="invalid-feedback">กรุณากรอกชื่อบทเรียน.</div>
                            <label for="lesson_content_1" class="form-label mt-2">เนื้อหาบทเรียนที่ 1<span class="text-danger">*</span></label>
                            <textarea name="lesson_content[]" id="lesson_content_1" required placeholder="กรอกเนื้อหาบทเรียน" class="form-control" rows="5"></textarea>
                            <div class="invalid-feedback">กรุณากรอกเนื้อหาบทเรียน.</div>
                            <label for="lesson_video_1" class="form-label mt-2">ลิงก์วิดีโอบทเรียนที่ 1<span class="text-danger">*</span></label>
                            <input type="url" name="lesson_video[]" id="lesson_video_1" required placeholder="ลิงก์วิดีโอของบทเรียน" class="form-control">
                            <div class="invalid-feedback">กรุณาใส่ลิงก์วิดีโอ.</div>
                        </div>
                    </div>
                    <button type="button" id="addLessonBtn" class="btn btn-success mb-3">เพิ่มบทเรียน</button>
                    <button type="button" id="removeLessonBtn" class="btn btn-danger mb-3">ลบบทเรียน</button>
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

    <script>
        let lessonCount = 1; // นับจำนวนบทเรียนเริ่มต้น

        // ฟังก์ชันเพิ่มบทเรียนใหม่
        document.getElementById('addLessonBtn').addEventListener('click', function() {
            lessonCount++; // เพิ่มตัวเลขบทเรียน

            // สร้างบล็อกบทเรียนใหม่
            const newLessonBlock = `
            <div class="lesson-block mb-3" id="lesson_block_${lessonCount}">
                <label for="lesson_title_${lessonCount}" class="form-label">ชื่อบทเรียนที่ ${lessonCount} <span class="text-danger">*</span></label>
                <input type="text" name="lesson_title[]" id="lesson_title_${lessonCount}" maxlength="100" required placeholder="กรอกชื่อบทเรียน ${lessonCount}" class="form-control">
                <div class="invalid-feedback">กรุณากรอกชื่อบทเรียน.</div>
                <label for="lesson_content_${lessonCount}" class="form-label mt-2">เนื้อหาบทเรียนที่ ${lessonCount}</label>
                <textarea name="lesson_content[]" id="lesson_content_${lessonCount}" required placeholder="กรอกเนื้อหาบทเรียน" class="form-control" rows="5"></textarea>
                <label for="lesson_video_${lessonCount}" class="form-label mt-2">ลิงก์วิดีโอบทเรียนที่ ${lessonCount}</label>
                <input type="url" name="lesson_video[]" id="lesson_video_${lessonCount}" required placeholder="ลิงก์วิดีโอของบทเรียน" ${lessonCount}" class="form-control">
            </div>
        `;

            // เพิ่มบล็อกใหม่เข้าไปในส่วนของบทเรียน
            document.getElementById('lesson-blocks').insertAdjacentHTML('beforeend', newLessonBlock);
        });

        // ฟังก์ชันลบบทเรียนล่าสุด
        document.getElementById('removeLessonBtn').addEventListener('click', function() {
            if (lessonCount > 1) { // หากมีมากกว่า 1 บทเรียน
                document.getElementById(`lesson_block_${lessonCount}`).remove(); // ลบบทเรียนล่าสุด
                lessonCount--; // ลดจำนวนบทเรียน
            }
        });
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