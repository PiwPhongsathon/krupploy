<?php
// เชื่อมต่อฐานข้อมูล
session_start();
require_once "components/database.php";



// ดึงข้อมูลครูจากตาราง teacher_profiles และ user
$sql = "SELECT tp.*, u.fname, u.lname, u.profile_pic 
        FROM teacher_profiles tp 
        JOIN user u ON tp.user_id = u.id
        WHERE u.role = 'teacher'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    $teachers = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $teachers = [];
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
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
                    <li><a href="infoteacher.php">คุณครู</a></li>
                </ul>
            </div>
            <a href="index.php">
                <div class="signup_button">
                    เข้าสู่ระบบ/สมัครสมาชิก
                </div>
            </a>
        </div>
    </nav>

    <div class="containner_program">
        <div class="main-content">
            <div class="header">
                <h2>คุณครู</h2>
                <div class="btn-group">
                    <a href="#" class="btn-addteacher" onclick="checkLoginBeforeRegister()">
                        <i class="fas fa-user "></i> ลงทะเบียนคุณครู
                    </a>
                </div>
            </div>
            <section class="teachers">
                <div class="box_teachers">
                    <?php foreach ($teachers as $teacher): ?>
                        <div class="teacher-card">
                            <div class="imgteacher">
                                <img src="<?php echo $teacher['profile_pic'] ? 'uploads/profiles/' . $teacher['profile_pic'] : 'img/teacher.png'; ?>" alt="Teacher" width="171" height="171">
                            </div>
                            <h3>ชื่อเล่น <?php echo $teacher['nickname']; ?></h3>
                            <p><?php echo $teacher['full_name']; ?></p>
                            <p>คณะ <?php echo $teacher['faculty']; ?></p>
                            <p>มหาวิทยาลัย <?php echo $teacher['university']; ?></p>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($teachers)): ?>
                        <p>ไม่พบข้อมูลครูในระบบ</p>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </div>

    <footer class="footer-07">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <h2 class="footer-heading"><a href="#" class="logo">KruPPloy</a></h2>
                    <p class="menu">
                        <a href="index.php">หน้าหลัก</a>
                        <a href="course.php">คอร์สเรียน</a>
                        <a href="#">โปรแกรมการสอน</a>
                        <a href="infoteacher.php">คุณครู</a>
                    </p>
                    <ul class="ftco-footer-social">
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook">
                                <span class="fab fa-facebook-f"></span>
                            </a>
                        </li>
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Line">
                                <span class="fab fa-line"></span> <!-- ใช้ไอคอน Line จาก Font Awesome -->
                            </a>
                        </li>
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Instagram">
                                <span class="fab fa-instagram"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <p class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright ©<script>
                            document.write(new Date().getFullYear());
                        </script>2024 All rights reserved | This template is made with <i class="ion-ios-heart" aria-hidden="true"></i> by <a href="#" target="_blank">Colorlib.com</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="./js/navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>

    <script>
        function checkLoginBeforeRegister() {
            // ตรวจสอบสถานะการเข้าสู่ระบบ (ตัวอย่างนี้สมมติว่ายังไม่ได้เข้าสู่ระบบ)
            var isLoggedIn = false; // ในกรณีจริง คุณต้องตรวจสอบสถานะการเข้าสู่ระบบจาก session หรือ cookie

            if (!isLoggedIn) {
                Swal.fire({
                    title: 'กรุณาเข้าสู่ระบบ',
                    text: 'คุณจำเป็นต้องเข้าสู่ระบบก่อนลงทะเบียนเป็นคุณครู',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ถ้าผู้ใช้คลิก "ตกลง" ให้ redirect ไปยังหน้าเข้าสู่ระบบ
                        window.location.href = 'index.php'; // แก้ไขเป็น URL ของหน้าเข้าสู่ระบบของคุณ
                    }
                });
            } else {
                // ถ้าเข้าสู่ระบบแล้ว ให้ redirect ไปยังหน้าลงทะเบียนคุณครู
                window.location.href = 'addteacher.php'; // แก้ไขเป็น URL ของหน้าลงทะเบียนคุณครูของคุณ
            }
        }
    </script>
</body>

</html>
<?php
$conn->close();
?>