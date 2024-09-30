<?php
session_start();
require_once "components/database.php";

// ดึงข้อมูลคอร์สพร้อมคะแนนเฉลี่ยจากฐานข้อมูล และจำกัดแค่ 3 คอร์ส
$sql = "
    SELECT c.*, COALESCE(AVG(r.rating), 0) AS average_rating, COUNT(r.rating) AS rating_count
    FROM courses c
    LEFT JOIN course_ratings r ON c.id = r.course_id
    GROUP BY c.id
    ORDER BY average_rating DESC
    LIMIT 3
";

$result = $conn->query($sql);

$sql_teachers = "SELECT tp.*, u.fname, u.lname, u.profile_pic 
        FROM teacher_profiles tp 
        JOIN user u ON tp.user_id = u.id
        WHERE u.role = 'teacher'
        LIMIT 3";


$result_teachers = $conn->query($sql_teachers);

$teachers = [];
if ($result_teachers->num_rows > 0) {
    while ($row = $result_teachers->fetch_assoc()) {
        $teachers[] = $row;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="stylesheet" href="./css/stlye.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php if (isset($_SESSION['error'])) : ?>
        <script>
            Swal.fire({
                title: 'ข้อมูลไม่ถูกต้อง!',
                text: "<?php echo $_SESSION['error']; ?>",
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'index.php'; // เปลี่ยนเส้นทางหลังจากแสดงข้อความ
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])) : ?>
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: "<?php echo $_SESSION['success']; ?>",
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                window.location.href = 'home.php'; // เปลี่ยนเส้นทางหลังจากแสดงข้อความ
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>


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
                    <li><a href="#">หน้าหลัก</a></li>
                    <li><a href="course.php">คอร์สเรียน</a></li>
                    <li><a href="#">โปรแกรมการสอน</a></li>
                    <li><a href="infoteacher.php">คุณครู</a></li>
                </ul>
            </div>
            <a href="#" onclick="openForm()">
                <div class="signup_button">
                    เข้าสู่ระบบ/สมัครสมาชิก
                </div>
            </a>
        </div>
    </nav>

    <section class="box_1">
        <div class="boxinbox">
            <div class="text_box1">
                <h1>แพลตฟอร์มเรียนออนไลน์</h1>
                เพิ่มพูนความรู้และทักษะพื้นฐานให้กับลูกน้อยของคุณด้วยหลักสูตรออนไลน์สำหรับชั้นประถม<br>
                ที่ครอบคลุมทุกวิชาหลักและเต็มไปด้วยกิจกรรมสนุกๆ
                <div>
                    <img src="img/3c.png" alt="" width="543" height="442">
                </div>
            </div>
            <div class="login-container" id="myForm">
                <div class="login-box">
                    <div class="exit">
                        <h1 onclick="closeForm()">X</h1>
                    </div>
                    <h2>เข้าสู่ระบบ</h2>
                    <form action="login.php" method="post">
                        <label for="email"></label>
                        <input type="email" id="email" name="email" placeholder="อีเมล" required>

                        <label for="password"></label>
                        <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required>

                        <button type="submit" name="login" class="login-button">เข้าสู่ระบบ</button>

                        <div class="signup-link">
                            ไม่มีบัญชี ? <a href="#" onclick="openForms()">สมัครสมาชิก</a>
                        </div>


                        <div class="divider">หรือ</div>

                    </form>

                    <div class="forgot-password">
                        <a href="forgot-password.php">ลืมรหัสผ่าน?</a>
                    </div>
                    <a href="google-login.php">
                        <button class="social-button google">
                            <img src="img/google-icon.png" alt="Google Logo"> Login with Google
                        </button>
                    </a>
                </div>
            </div>

            <div class="signup-container" id="signupfrom">
                <div class="login-box">
                    <div class="exit">
                        <h1 onclick="closeForms()">X</h1>
                    </div>
                    <h2>สมัครสมาชิก</h2>
                    <form action="register.php" method="post">
                        <label for="email"></label>
                        <input type="email" id="email" name="email" placeholder="อีเมล" required>

                        <label for="password"></label>
                        <input type="password" id="password" name="password" placeholder="รหัสผ่าน" required>

                        <label for="fname"></label>
                        <input type="text" id="fname" name="fname" placeholder="ชื่อ" required>

                        <label for="lname"></label>
                        <input type="text" id="lname" name="lname" placeholder="นามสกุล" required>

                        <button type="submit" name="submit" value="submit" class="login-button">สมัครสมาชิก</button>

                        <div class="signup-link">
                            มีบัญชีอยู่แล้ว ? <a href="#" onclick="openForm()">เข้าสู่ระบบ</a>
                        </div>

                        <div class="divider">หรือ</div>

                        <button class="social-button google">
                            <img src="img/google-icon.png" alt="Google Logo"> Login with Google
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <section class="features">
        <div class="feature">
            <img src="img/icon1.png" alt="Icon" width="72.03" height="69.97">
            <div class="text-feat">
                <h3>50+หลักสูตรสำหรับเด็ก</h3>
                <p>ร่วมเปิดโลกการเรียนรู้อย่างสนุก
                    สนานกับหลักสูตรออนไลน์สำหรับ
                    เด็กประถม</p>
            </div>
        </div>
        <div class="feature">
            <img src="img/icon2.png" alt="Icon" width="72.03" height="81.52">
            <div class="text-feat">
                <h3>นักศึกษาครู</h3>
                <p>เรียนรู้สนุกกับคุณครูน่ารักที่เต็มไป
                    ด้วยความใส่ใจและความเข้าใจใน
                    เด็ก ๆ</p>
            </div>
        </div>
        <div class="feature">
            <img src="img/icon3.png" alt="Icon" width="78" height="78">
            <div class="text-feat">
                <h3>เรียนได้ทุกที่ทุกเวลา</h3>
                <p>การเรียนรู้ที่สามารถเรียนได้ทุกที่ทุก
                    เวลาคุณสามารถจัดการเวลาเรียนได้ตามสะดวกของเด็กๆ</p>
            </div>
        </div>
    </section>


    <div class="containner_program">
        <div class="main-content">
            <h1>คอร์สเรียน</h1>
            <section class="dashboard-content">
                <?php
                if ($result->num_rows > 0) {
                    while ($course = $result->fetch_assoc()) {
                        // ดึงข้อมูลครูผู้สอน
                        $teacher_id = $course['user_id'];
                        $teacher_query = "SELECT fname, lname, bank_slip_image FROM user WHERE id = '$teacher_id'";
                        $teacher_result = mysqli_query($conn, $teacher_query);
                        $teacher = mysqli_fetch_assoc($teacher_result);
                        $teacher_name = $teacher['fname'] . ' ' . $teacher['lname'];
                        $bank_slip_image = $teacher['bank_slip_image'];
                ?>
                        <div class="boxcourse" onclick="location.href='course_infonologin.php?course_id=<?= $course['id']; ?>'">
                            <img src="uploads/covers/<?php echo $course['cover_image']; ?>" alt="course" class="course-image">
                            <div class="textboxcourse">
                                <p class="course-subject">วิชา <?php echo $course['subject']; ?></p>
                                <h5 class="course-title1"><?php echo $course['course_name']; ?></h5>
                                <p class="course-content1"><?php echo $course['course_content']; ?></p>
                                <div class="rate-price">
                                    <div>
                                        <!-- แสดงดาวที่ได้จากคะแนนเฉลี่ย -->
                                        <?php
                                        $average_rating = round($course['average_rating']);
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $average_rating) {
                                                echo '<span class="fa fa-star checked"></span>';
                                            } else {
                                                echo '<span class="fa fa-star"></span>';
                                            }
                                        }
                                        ?>
                                        <span>(<?php echo $course['rating_count']; ?>)</span>
                                    </div>
                                    <h4 class="price_course"><?php echo $course['price']; ?> บาท</h4>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>ไม่มีคอร์สที่พบ</p>";
                }
                ?>
            </section>
        </div>
    </div>

    <div class="boxcoursebtn-seemore">
        <a href="course.php">
            <div class="coursebtn-seemore">
                <h5>ดูเพิ่มเติม</h5>
            </div>
        </a>
    </div>




    <section class="program">
        <h1>โปรแกรมการสอน</h1>
        <div class="program-content">
            <img src="img/program1.png" alt="Program" width="553" height="376">
            <div>
                <h5>การเรียนการสอน</h5>
                <p>โปรแกรมการสอนออนไลน์ของเรานำเสนอประสบการณ์<br>
                    การเรียนรู้ที่ยอดเยี่ยมผ่านวิดีโอที่มีคุณภาพสูงและเข้า<br>
                    ถึงการบันทึกวิดีโอสำหรับการทบทวนได้ทุกเมื่อด้วย<br>
                    ฟีเจอร์ที่ทันสมัยและเข้าใจง่ายเราทำให้การเรียนรู้เป็น<br>
                    เรื่องสนุกและมีประสิทธิภาพคุณสามารถเรียนรู้ได้ทุกที่<br>
                    ทุกเวลาตามความสะดวกของคุณ</p>
            </div>
        </div>
        <div class="program-content2">
            <div>
                <h5>การทำแบบทดสอบหลังเรียน</h5>
                <p>โปรแกรมการสอนออนไลน์ของเรามาพร้อมกับการทำแบบทดสอบ<br>
                    หลังเรียนที่ช่วยเสริมสร้างความเข้าใจและประเมินผลการเรียนรู้<br>
                    ของผู้เรียน คุณสามารถเข้าถึงแบบทดสอบที่หลากหลายและ<br>
                    ตรงกับเนื้อหาที่ได้เรียนมา เพื่อให้มั่นใจว่าคุณเข้าใจเนื้อหา<br>
                    อย่างถ่องแท้</p>
            </div>
            <img src="img/program2.png" alt="Program" width="396" height="374">
        </div>
    </section>

    <section class="teachers">
        <h1>คุณครู</h1>
        <div class="box_teachers">
            <?php foreach ($teachers as $teacher): ?>
                <div class="teacher-card">
                    <div class="imgteacher">
                        <img src="<?php echo $teacher['profile_pic'] ? 'uploads/profiles/' . $teacher['profile_pic'] : 'img/teacher.png'; ?>" alt="Teacher" width="171" height="171">
                    </div>
                    <h3><?php echo $teacher['nickname']; ?></h3>
                    <p><?php echo $teacher['full_name']; ?></p>
                    <p><?php echo $teacher['faculty']; ?></p>
                    <p><?php echo $teacher['university']; ?></p>
                </div>
            <?php endforeach; ?>

            <?php if (empty($teachers)): ?>
                <p>ไม่พบข้อมูลครูในระบบ</p>
            <?php endif; ?>
        </div>
    </section>


    <footer class="footer-07">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <h2 class="footer-heading"><a href="#" class="logo">KruPPloy</a></h2>
                    <p class="menu">
                        <a href="#">หน้าหลัก</a>
                        <a href="course.php">คอร์สเรียน</a>
                        <a href="#">โปรแกรมการสอน</a>
                        <a href="infoteacher">คุณครู</a>
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



    <script src="js/navbar.js"></script>
</body>

</html>

<?php

if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    session_destroy();
}

?>