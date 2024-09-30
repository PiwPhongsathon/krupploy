<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="stylesheet" href="./css/test.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            ?>
        </div>
    <?php endif; ?>


    <?php if (isset($_SESSION['error'])) : ?>
        <div class="error">
            <?php
            echo $_SESSION['error'];
            ?>
        </div>
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
                    <li><a href="index.php">คุณครู</a></li>
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

                        <button type="submit" name="submit" class="login-button">เข้าสู่ระบบ</button>

                        <div class="signup-link">
                            ไม่มีบัญชี ? <a href="#" onclick="openForms()">สมัครสมาชิก</a>
                        </div>

                        <div class="divider">หรือ</div>

                        <button class="social-button facebook">
                            <img src="img/facebook-icon.png.png" alt="Facebook Logo"> Login with Facebook
                        </button>

                        <button class="social-button google">
                            <img src="img/google-icon.png" alt="Google Logo"> Login with Google
                        </button>
                    </form>
                </div>
            </div>

            <div class="signup-container" id="signupfrom">
                <div class="login-box">
                    <div class="exit">
                        <h1 onclick="closeForms()">X</h1>
                    </div>
                    <h2>สมัครสมาชิก</h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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

                        <button class="social-button facebook">
                            <img src="img/facebook-icon.png.png" alt="Facebook Logo"> Login with Facebook
                        </button>

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


    <section class="courses__">
        <h1>คอร์สเรียน</h1>
        <div class="courses">
            <div class="course-card">
                <div class="boximg-course">
                    <img class="imgcourse" src="img/course1.png" alt="Icon">
                </div>
                <div class="textboxcourse">
                    <p>วิชา ภาษาอังกฤษ</p>
                    <h5>หลักภาษาและการใช้ภาษา</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est, quos ducimus optio perspiciatis iusto deserunt eos animi aliquid quibusdam facere enim obcaecati vero deleniti aperiam, doloremque consectetur vitae,</p>
                    <div class="rate-price">
                        <div>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <h5>B350.00</h5>
                    </div>
                </div>
            </div>
            <div class="course-card">
                <div class="boximg-course">
                    <img class="imgcourse" src="img/course1.png" alt="Icon">
                </div>
                <div class="textboxcourse">
                    <p>วิชา ภาษาอังกฤษ</p>
                    <h5>หลักภาษาและการใช้ภาษา</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est, quos ducimus optio perspiciatis iusto deserunt eos animi aliquid quibusdam facere enim obcaecati vero deleniti aperiam, doloremque consectetur vitae,</p>
                    <div class="rate-price">
                        <div>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <h5>B350.00</h5>
                    </div>
                </div>
            </div>
            <div class="course-card">
                <div class="boximg-course">
                    <img class="imgcourse" src="img/course1.png" alt="Icon">
                </div>
                <div class="textboxcourse">
                    <p>วิชา ภาษาอังกฤษ</p>
                    <h5>หลักภาษาและการใช้ภาษา</h5>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est, quos ducimus optio perspiciatis iusto deserunt eos animi aliquid quibusdam facere enim obcaecati vero deleniti aperiam, doloremque consectetur vitae,</p>
                    <div class="rate-price">
                        <div>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <h5>B350.00</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="boxcoursebtn-seemore">
        <div class="coursebtn-seemore">
            <h5>ดูเพิ่มเติม</h5>
        </div>
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
            <div class="teacher-card">
                <div class="imgteacher">
                    <img src="img/teacher.png" alt="Teacher" width="171" height="171">
                </div>
                <h3>ครูพลอย</h3>
                <p>นางสาว วรณัฐ ใสยาวงศ์</p>
                <p>คณะศึกษาศาสตร์ </p>
                <p>มหาวิทยาลัยศิลปากร</p>
            </div>
            <div class="teacher-card">
                <div class="imgteacher">
                    <img src="img/teacher.png" alt="Teacher" width="171" height="171">
                </div>
                <h3>ครูพลอย</h3>
                <p>นางสาว วรณัฐ ใสยาวงศ์</p>
                <p>คณะศึกษาศาสตร์ </p>
                <p>มหาวิทยาลัยศิลปากร</p>
            </div>
            <div class="teacher-card">
                <div class="imgteacher">
                    <img src="img/teacher.png" alt="Teacher" width="171" height="171">
                </div>
                <h3>ครูพลอย</h3>
                <p>นางสาว วรณัฐ ใสยาวงศ์</p>
                <p>คณะศึกษาศาสตร์ </p>
                <p>มหาวิทยาลัยศิลปากร</p>
            </div>
        </div>
    </section>

    <footer>

    </footer>
    <script src="js/navbar.js"></script>
</body>

</html>

<?php

if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    session_destroy();
}

?>