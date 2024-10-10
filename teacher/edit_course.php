<?php
session_start();
require_once "../components/database.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // ดึงข้อมูลคอร์สจากฐานข้อมูล
    $query = "SELECT * FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);
    $course = mysqli_fetch_assoc($result);

    // ดึงข้อมูลบทเรียนที่เกี่ยวข้องกับคอร์ส
    $lesson_query = "SELECT * FROM lessons WHERE course_id = $course_id";
    $lesson_result = mysqli_query($conn, $lesson_query);

    if (isset($_POST['submit'])) {
        $course_name = $_POST['course_name'];
        $course_name = filter_var($course_name, FILTER_SANITIZE_STRING);

        $course_content = $_POST['course_content'];
        $course_content = filter_var($course_content, FILTER_SANITIZE_STRING);

        $subject = $_POST['subject'];
        $subject = filter_var($subject, FILTER_SANITIZE_STRING);

        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Handling file uploads
        if (!empty($_FILES['cover_image']['name'])) {
            $cover_image = $_FILES['cover_image']['name'];
            $cover_image = filter_var($cover_image, FILTER_SANITIZE_STRING);
            $cover_ext = pathinfo($cover_image, PATHINFO_EXTENSION);
            $rename_cover = unique_id() . '.' . $cover_ext;
            $cover_tmp_name = $_FILES['cover_image']['tmp_name'];
            $cover_folder = '../uploads/covers/' . $rename_cover;

            // Move uploaded file
            move_uploaded_file($cover_tmp_name, $cover_folder);

            // Update with new cover image
            $update_cover = ", cover_image = '$rename_cover'";
        } else {
            $update_cover = '';
        }

        // Update data in the database
        $query = "UPDATE courses SET course_name = '$course_name', course_content = '$course_content', subject = '$subject', price = '$price' $update_cover WHERE id = $course_id";

        $result = mysqli_query($conn, $query);


        if ($result) {
            $_SESSION['success'] = "Course updated successfully";
        } else {
            $_SESSION['error'] = "Something went wrong";
        }
    }

    // จัดการการอัปเดตบทเรียน
    if (isset($_POST['submit'])) {
        $lesson_titles = $_POST['lesson_title'];
        $lesson_contents = $_POST['lesson_content'];
        $lesson_videos = $_POST['video_link'];

        foreach ($lesson_titles as $index => $title) {
            if (!empty($title) && isset($lesson_contents[$index]) && isset($lesson_videos[$index])) {
                $lesson_title = filter_var($title, FILTER_SANITIZE_STRING);
                $lesson_content = filter_var($lesson_contents[$index], FILTER_SANITIZE_STRING);
                $lesson_video = filter_var($lesson_videos[$index], FILTER_SANITIZE_URL);

                // ดึง id ของบทเรียนจากฐานข้อมูล
                $lesson_id_query = "SELECT id FROM lessons WHERE course_id = $course_id LIMIT 1 OFFSET $index";
                $lesson_id_result = mysqli_query($conn, $lesson_id_query);
                $lesson_id_row = mysqli_fetch_assoc($lesson_id_result);
                $lesson_id = $lesson_id_row['id'];

                // อัปเดตบทเรียน
                $update_lesson_query = "UPDATE lessons SET lesson_title = '$lesson_title', lesson_content = '$lesson_content', video_link = '$lesson_video' WHERE id = $lesson_id";
                $update_result = mysqli_query($conn, $update_lesson_query);

                if (!$update_result) {
                    echo "Error updating lesson: " . mysqli_error($conn);
                }
            }
        }


        $_SESSION['success'] = "Lessons updated successfully";
    }
} else {
    echo "No course ID provided";
    exit;
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
                <h3 class="text-center mb-4">แก้ไขคอร์ส</h3>
                <form action="" method="post" enctype="multipart/form-data" id="courseForm" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">ชื่อคอร์ส <span class="text-danger">*</span></label>
                        <input type="text" name="course_name" id="course_name" maxlength="100" required value="<?= $course['course_name']; ?>" class="form-control">
                        <div class="invalid-feedback">กรุณากรอกชื่อคอร์ส.</div>
                    </div>
                    <div class="mb-3">
                        <label for="course_content" class="form-label">เนื้อหาคอร์ส <span class="text-danger">*</span></label>
                        <textarea name="course_content" id="course_content" class="form-control" required maxlength="1000" rows="5"><?= $course['course_content']; ?></textarea>
                        <div class="invalid-feedback">กรุณากรอกเนื้อหาคอร์ส.</div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">ราคา <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price" maxlength="100" required value="<?= $course['price']; ?>" class="form-control">
                        <div class="invalid-feedback">กรุณากรอกราคา.</div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">หมวดหมู่รายวิชา <span class="text-danger">*</span></label>
                        <select name="subject" id="subject" class="form-select" required>
                            <option value="ภาษาอังกฤษ" <?= $course['subject'] == 'ภาษาอังกฤษ' ? 'selected' : ''; ?>>ภาษาอังกฤษ</option>
                            <option value="ภาษาไทย" <?= $course['subject'] == 'ภาษาไทย' ? 'selected' : ''; ?>>ภาษาไทย</option>
                            <option value="คณิตศาสตร์" <?= $course['subject'] == 'คณิตศาสตร์' ? 'selected' : ''; ?>>คณิตศาสตร์</option>
                        </select>
                        <div class="invalid-feedback">กรุณาเลือกหมวดหมู่รายวิชา.</div>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">ภาพปกคลิป</label>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-control">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="video_link" class="form-label">ลิงก์คลิปวีดีโอ (ไม่สามารถเปลี่ยนได้)</label>
                        <input type="url" name="video_link" id="video_link" value="<?= $course['video_link']; ?>" class="form-control" readonly>
                    </div> -->

                    <section class="lesson-form mt-5">
                        <h4 class="text-center">จัดการบทเรียน</h4>
                        <form action="" method="post" id="lessonForm">
                            <div id="lesson-blocks">
                                <?php
                                $lesson_index = 1;
                                $lesson_count = mysqli_num_rows($lesson_result); // ตรวจสอบจำนวนบทเรียน
                                if ($lesson_count > 0) {
                                    while ($lesson = mysqli_fetch_assoc($lesson_result)) {
                                ?>
                                        <div class="lesson-block mb-3">
                                            <label for="lesson_title_<?= $lesson_index ?>" class="form-label">ชื่อบทเรียนที่ <?= $lesson_index ?><span class="text-danger">*</span></label>
                                            <input type="text" name="lesson_title[]" id="lesson_title_<?= $lesson_index ?>" maxlength="100" required placeholder="กรอกชื่อบทเรียน" class="form-control" value="<?= $lesson['lesson_title']; ?>">
                                            <div class="invalid-feedback">กรุณากรอกชื่อบทเรียน.</div>
                                            <label for="lesson_content_<?= $lesson_index ?>" class="form-label mt-2">เนื้อหาบทเรียนที่ <?= $lesson_index ?><span class="text-danger">*</span></label>
                                            <textarea name="lesson_content[]" id="lesson_content_<?= $lesson_index ?>" required placeholder="กรอกเนื้อหาบทเรียน" class="form-control" rows="5"><?= $lesson['lesson_content']; ?></textarea>
                                            <div class="invalid-feedback">กรุณากรอกเนื้อหาบทเรียน.</div>
                                            <label for="video_link_<?= $lesson_index ?>" class="form-label">ลิงก์คลิปวีดีโอ (ไม่สามารถเปลี่ยนได้)</label>
                                            <input type="url" name="video_link[]" id="video_link_<?= $lesson_index ?>" value="<?= htmlspecialchars($lesson['video_link']); ?>" class="form-control" readonly>
                                        </div>
                                    <?php
                                        $lesson_index++;
                                    }
                                    ?>
                                    <!-- <div class="text-center">
                                        <button type="submit" name="update_lessons" class="btn btn-secondary">อัปเดตคอร์สและบทเรียน</button>
                                    </div> -->
                                <?php
                                } else {
                                    echo '<p class="text-center">ไม่มีบทเรียนให้จัดการ</p>'; // แสดงข้อความเมื่อไม่มีบทเรียน
                                }
                                ?>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="submit" id="updateCourseBtn" class="btn btn-primary">อัปเดตคอร์สและบทเรียน</button>
                            </div>
                        </form>
                    </section>

                </form>
            </div>


        </section>

    </div>

    <script src="../js/navbar.js"></script>
    <script src="../js/dropdownmenu.js"></script>

    <script>
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
        })();
    </script>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            Swal.fire({
                title: 'อัปเดตคอร์สสำเร็จ!',
                text: 'คุณได้อัปเดตคอร์สเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'mycourse.php'; // เปลี่ยนเส้นทางไปยังหน้า mycourse.php
                }
            });
        </script>
        <?php unset($_SESSION['success']); // ลบค่าจากเซสชันหลังจากแสดงผลแล้ว 
        ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่สามารถอัปเดตคอร์สได้ โปรดลองอีกครั้ง',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'edit_course.php?id=<?= $course_id; ?>'; // เปลี่ยนเส้นทางไปยังหน้าแก้ไขคอร์ส
                }
            });
        </script>
        <?php unset($_SESSION['error']); // ลบค่าจากเซสชันหลังจากแสดงผลแล้ว 
        ?>
    <?php endif; ?>
</body>

</html>