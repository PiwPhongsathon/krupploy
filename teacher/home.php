<?php
session_start();

// ตรวจสอบว่า userid ถูกตั้งค่าในเซสชันหรือไม่
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit(); // หยุดการทำงานของสคริปต์หลังจากการเปลี่ยนเส้นทาง
}
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KruPPloy</title>
        <link rel="stylesheet" href="../css/teacher.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    </head>

    <body>
        <?php include '../components/teacher_header.php'; ?>

        <section class="box_1">
            <div class="boxinbox">
                <div class="text_box1">
                    <h1>แพลตฟอร์มเรียนออนไลน์</h1>
                    <p>เพิ่มพูนความรู้และทักษะพื้นฐานให้กับลูกน้อยของคุณด้วยหลักสูตรออนไลน์สำหรับชั้นประถม<br>
                        ที่ครอบคลุมทุกวิชาหลักและเต็มไปด้วยกิจกรรมสนุกๆ</p>
                </div>
                <div>
                    <img src="../img/3c.png" alt="" width="543" height="442">
                </div>
        </section>

        <section class="features">
            <div class="feature">
                <img src="../img/icon1.png" alt="Icon" width="72.03" height="69.97">
                <div class="text-feat">
                    <h3>50+หลักสูตรสำหรับเด็ก</h3>
                    <p>ร่วมเปิดโลกการเรียนรู้อย่างสนุก
                        สนานกับหลักสูตรออนไลน์สำหรับ
                        เด็กประถม</p>
                </div>
            </div>
            <div class="feature">
                <img src="../img/icon2.png" alt="Icon" width="72.03" height="81.52">
                <div class="text-feat">
                    <h3>นักศึกษาครู</h3>
                    <p>เรียนรู้สนุกกับคุณครูน่ารักที่เต็มไป
                        ด้วยความใส่ใจและความเข้าใจใน
                        เด็ก ๆ</p>
                </div>
            </div>
            <div class="feature">
                <img src="../img/icon3.png" alt="Icon" width="78" height="78">
                <div class="text-feat">
                    <h3>เรียนได้ทุกที่ทุกเวลา</h3>
                    <p>การเรียนรู้ที่สามารถเรียนได้ทุกที่ทุก
                        เวลาคุณสามารถจัดการเวลาเรียนได้ตามสะดวกของเด็กๆ</p>
                </div>
            </div>
        </section>


        <section class="program">
            <h1>โปรแกรมการสอน</h1>
            <div class="program-content">
                <img src="../img/program1.png" alt="Program" width="553" height="376">
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
                <img src="../img/program2.png" alt="Program" width="396" height="374">
            </div>
        </section>


        <footer>

        </footer>
        <script src="navbar.js"></script>
        <script src="../js/dropdownmenu.js"></script>
    </body>

    </html>

