<?php

require_once "components/database.php";
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $nickname = $_POST['nickname'];
    $full_name = $_POST['full_name'];
    $faculty = $_POST['faculty'];
    $university = $_POST['university'];

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลลงในตาราง
    $sql = "INSERT INTO teacher_profiles (user_id, nickname, full_name, faculty, university, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        // ผูกค่าจากฟอร์มกับคำสั่ง SQL
        $stmt->bind_param('issss', $_SESSION['userid'], $nickname, $full_name, $faculty, $university);

        // ดำเนินการเพิ่มข้อมูล
        if ($stmt->execute()) {
            $successMessage = "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'ลงทะเบียนสำเร็จ',
                    text: 'ข้อมูลถูกส่งไปแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'infoteacherlogin.php'; // นำทางไปยัง index.php
                });
            </script>";
        } else {
            $errorMessage = "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้',
                    showConfirmButton: true
                });
            </script>";
        }

        // ปิดการเชื่อมต่อ statement
        $stmt->close();
    } else {
        $errorMessage = "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเตรียมคำสั่ง SQL ได้',
                showConfirmButton: true
            });
        </script>";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include './components/user_header.php'; ?>

    <div class="containner_program">
        <div class="main-content">
            <div class="container my-5">
                <section class="">
                    <div class="">
                        <h2 class="text-center mb-4">ลงทะเบียนคุณครู</h2>
                        <form action="" method="post" enctype="multipart/form-data" id="courseForm" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="nickname" class="form-label">ชื่อเล่น <span class="text-danger">*</span></label>
                                <input type="text" name="nickname" id="nickname" maxlength="100" required placeholder="กรอกชื่อเล่น" class="form-control">
                                <div class="invalid-feedback">กรุณากรอกชื่อเล่น.</div>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">ชื่อจริง-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" id="full_name" maxlength="100" required placeholder="กรอกชื่อจริง-นามสกุล" class="form-control">
                                <div class="invalid-feedback">กรุณากรอกชื่อจริง.</div>
                            </div>
                            <div class="mb-3">
                                <label for="faculty" class="form-label">คณะ <span class="text-danger">*</span></label>
                                <select name="faculty" id="faculty" class="form-select" required>
                                    <option value="" disabled selected>เลือกคณะ</option>
                                    <option value="ศึกษาศาสตร์">ศึกษาศาสตร์</option>
                                    <option value="ครุศาสตร์">ครุศาสตร์</option>
                                    <option value="มนุษยศาสตร์และสังคมศาสตร์">มนุษยศาสตร์และสังคมศาสตร์</option>
                                    <option value="วิทยาศาสตร์">วิทยาศาสตร์</option>
                                    <option value="วิศวกรรมศาสตร์">วิศวกรรมศาสตร์</option>
                                    <option value="แพทยศาสตร์">แพทยศาสตร์</option>
                                    <option value="พยาบาลศาสตร์">พยาบาลศาสตร์</option>
                                    <option value="เภสัชศาสตร์">เภสัชศาสตร์</option>
                                    <option value="บริหารธุรกิจ">บริหารธุรกิจ</option>
                                    <option value="นิติศาสตร์">นิติศาสตร์</option>
                                    <option value="ศิลปกรรมศาสตร์">ศิลปกรรมศาสตร์</option>
                                    <option value="เทคโนโลยีสารสนเทศ">เทคโนโลยีสารสนเทศ</option>
                                    <option value="เทคโนโลยีการเกษตร">เทคโนโลยีการเกษตร</option>
                                    <option value="อุตสาหกรรมเกษตร">อุตสาหกรรมเกษตร</option>
                                    <option value="สถาปัตยกรรมศาสตร์">สถาปัตยกรรมศาสตร์</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกคณะ</div>
                            </div>
                            <div class="mb-3">
                                <label for="university" class="form-label">มหาวิทยาลัย <span class="text-danger">*</span></label>
                                <select name="university" id="university" class="form-select" required>
                                    <option value="" disabled selected>เลือกมหาวิทยาลัย</option>
                                    <option value="จุฬาลงกรณ์มหาวิทยาลัย">จุฬาลงกรณ์มหาวิทยาลัย</option>
                                    <option value="มหาวิทยาลัยธรรมศาสตร์">มหาวิทยาลัยธรรมศาสตร์</option>
                                    <option value="มหาวิทยาลัยมหิดล">มหาวิทยาลัยมหิดล</option>
                                    <option value="มหาวิทยาลัยเกษตรศาสตร์">มหาวิทยาลัยเกษตรศาสตร์</option>
                                    <option value="มหาวิทยาลัยศิลปากร">มหาวิทยาลัยศิลปากร</option>
                                    <option value="มหาวิทยาลัยเชียงใหม่">มหาวิทยาลัยเชียงใหม่</option>
                                    <option value="มหาวิทยาลัยขอนแก่น">มหาวิทยาลัยขอนแก่น</option>
                                    <option value="มหาวิทยาลัยสงขลานครินทร์">มหาวิทยาลัยสงขลานครินทร์</option>
                                    <option value="มหาวิทยาลัยบูรพา">มหาวิทยาลัยบูรพา</option>
                                    <option value="มหาวิทยาลัยนเรศวร">มหาวิทยาลัยนเรศวร</option>
                                    <option value="มหาวิทยาลัยรามคำแหง">มหาวิทยาลัยรามคำแหง</option>
                                    <option value="มหาวิทยาลัยศรีนครินทรวิโรฒ">มหาวิทยาลัยศรีนครินทรวิโรฒ</option>
                                    <!-- มหาวิทยาลัยราชภัฏ -->
                                    <option value="มหาวิทยาลัยราชภัฏสวนสุนันทา">มหาวิทยาลัยราชภัฏสวนสุนันทา</option>
                                    <option value="มหาวิทยาลัยราชภัฏจันทรเกษม">มหาวิทยาลัยราชภัฏจันทรเกษม</option>
                                    <option value="มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา">มหาวิทยาลัยราชภัฏบ้านสมเด็จเจ้าพระยา</option>
                                    <option value="มหาวิทยาลัยราชภัฏพระนคร">มหาวิทยาลัยราชภัฏพระนคร</option>
                                    <option value="มหาวิทยาลัยราชภัฏวไลยอลงกรณ์">มหาวิทยาลัยราชภัฏวไลยอลงกรณ์</option>
                                    <option value="มหาวิทยาลัยราชภัฏยะลา">มหาวิทยาลัยราชภัฏยะลา</option>
                                    <option value="มหาวิทยาลัยราชภัฏร้อยเอ็ด">มหาวิทยาลัยราชภัฏร้อยเอ็ด</option>
                                    <option value="มหาวิทยาลัยราชภัฏนครราชสีมา">มหาวิทยาลัยราชภัฏนครราชสีมา</option>
                                    <option value="มหาวิทยาลัยราชภัฏกาญจนบุรี">มหาวิทยาลัยราชภัฏกาญจนบุรี</option>
                                    <option value="มหาวิทยาลัยราชภัฏสุราษฎร์ธานี">มหาวิทยาลัยราชภัฏสุราษฎร์ธานี</option>
                                    <option value="มหาวิทยาลัยราชภัฏอุดรธานี">มหาวิทยาลัยราชภัฏอุดรธานี</option>
                                    <option value="มหาวิทยาลัยราชภัฏภูเก็ต">มหาวิทยาลัยราชภัฏภูเก็ต</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกมหาวิทยาลัย</div>
                            </div>
                            <div class="text-center">
                                <button type="submit" id="addCourseBtn" class="btn btn-primary">ส่งข้อมูล</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php
    // แสดงข้อความ SweetAlert
    if (!empty($successMessage)) {
        echo $successMessage;
    } elseif (!empty($errorMessage)) {
        echo $errorMessage;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-DyZgW4/uQf4IS+lS/gcex/NI05L/Jd4uqO+BCus1AzE4Jxt3dY+dxI0jxQZr3L3" crossorigin="anonymous"></script>
    <script>
        // ฟังก์ชันสำหรับตรวจสอบฟอร์ม
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>

    <script>
        // ฟังก์ชันในการยืนยันการบันทึกข้อมูล
        function confirmSave() {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "ข้อมูลของคุณจะถูกบันทึก!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, บันทึกข้อมูล!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // เมื่อผู้ใช้กดยืนยัน, ส่งฟอร์ม
                    document.getElementById('courseForm').submit();
                }
            });
        }

        // เรียกใช้ฟังก์ชัน confirmSave แทนการส่งฟอร์ม
        document.getElementById('addCourseBtn').addEventListener('click', function(event) {
            event.preventDefault(); // ป้องกันการส่งฟอร์มทันที

            // ตรวจสอบความถูกต้องของฟอร์มก่อน
            var form = document.getElementById('courseForm');
            if (form.checkValidity()) {
                confirmSave(); // เรียกฟังก์ชันยืนยัน
            } else {
                form.classList.add('was-validated'); // เพิ่มคลาสเพื่อแสดงผลการตรวจสอบ
            }
        });
    </script>

    <script src="navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>
</body>

</html>