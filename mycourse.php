<?php

session_start();

if (!$_SESSION['userid']) {
    header("Location: index.php");
} else {

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KruPPloy</title>
        <link rel="stylesheet" href="./css/home.css">
        <link rel="stylesheet" href="./css/course.css">
        <link rel="stylesheet" href="./css/program.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
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
                <section class="watch-video">
                    <div class="video-container">
                        <div class="video">
                            <video src="images/vid-1.mp4" controls poster="images/post-1-1.png" id="video"></video>
                        </div>
                        <p>วิชา ภาษาอังกฤษ</p>
                        <h4>หลักภาษาและการใช้ภาษา</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Est, quos ducimus optio perspiciatis iusto deserunt eos animi aliquid quibusdam facere enim obcaecati vero deleniti aperiam, doloremque consectetur vitae,</p>
                        <button>ทำแบบฝึกหัด</button>
                    </div>
                </section>
            </div>
        </div>
        <script src="navbar.js"></script>
        <script src="./js/dropdownmenu.js"></script>
    </body>

    </html>

<?php } ?>