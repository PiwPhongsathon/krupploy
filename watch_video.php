<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลของคอร์สที่กำลังจะดูวิดีโอ
if (isset($_GET['course_id']) && isset($_GET['lesson_id'])) {
    $course_id = intval($_GET['course_id']);
    $lesson_id = intval($_GET['lesson_id']); // ดึง lesson_id

    // ดึงข้อมูลคอร์ส
    $query = "SELECT * FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "ไม่พบคอร์สนี้!";
        exit();
    }

    // ดึงข้อมูลบทเรียนเฉพาะที่ระบุ
    $lesson_query = "SELECT * FROM lessons WHERE id = $lesson_id AND course_id = $course_id"; // เพิ่มเงื่อนไขให้ตรงกับ course_id
    $lesson_result = mysqli_query($conn, $lesson_query);

    if (mysqli_num_rows($lesson_result) > 0) {
        $lesson = mysqli_fetch_assoc($lesson_result);
    } else {
        echo "ไม่พบบทเรียนนี้!";
        exit();
    }
} else {
    echo "ไม่พบคอร์สหรือบทเรียนที่ต้องการ!";
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* ตั้งค่าพื้นหลังเป็นรูปภาพ */
        body {
            font-family: "Noto Sans Thai";
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('imagehappy.png');
            /* ใส่ path รูปภาพ */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            /* เพิ่มการจัดเรียงแนวตั้ง */
        }

        .video-player {
            background-color: rgba(245, 168, 142, 0.9);
            /* ทำให้มีความโปร่งแสง */
            padding: 10px;
            border-radius: 10px;
            width: 1200px;
            height: 700px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 20px;
            /* เพิ่มระยะห่างด้านล่าง */
        }

        iframe,
        video {
            width: 100%;
            height: 90%;
            border-radius: 10px;
            
        }

        .controls {
            background-color: rgba(248, 193, 125, 0.9);
            /* ทำให้โปร่งแสง */
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px;
            margin-top: 10px;
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
        }

        .play-pause {
            background-color: #d85d4d;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
        }

        .play-pause:before {
            content: '\25BA';
            color: #fff;
        }

        .play-pause.pause:before {
            content: '\23F8';
            /* Pause icon */
        }

        .progress-bar {
            flex-grow: 1;
            margin: 0 10px;
            position: relative;
            background-color: #f9d5a5;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress {
            width: 0;
            height: 100%;
            background-color: #d85d4d;
            transition: width 0.1s;
        }

        .time {
            color: #333;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .lesson-title {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .lesson-content {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            width: 90%;
            /* เปลี่ยนจาก 1100px เป็น 90% */
            max-width: 1100px;
            /* เพิ่ม max-width เพื่อควบคุมขนาดสูงสุด */
            text-align: center;
            /* กำหนดความกว้างของเนื้อหาบทเรียน */
        }

        @media (max-width: 768px) {
            .video-player {
                width: 95%;
                /* ขนาดที่เล็กลงในหน้าจอเล็ก */
            }
        }
    </style>
</head>

<body>
    <button class="back-button" onclick="goBack()">ย้อนกลับ</button>
    <div class="lesson-content">
        <strong><?= $lesson['lesson_title']; ?></strong>
    </div>

    <div class="video-player">
        <?php
        // ตรวจสอบลิงก์วิดีโอและใช้ iframe สำหรับ Google Drive หรือ Dropbox
        if (strpos($lesson['video_link'], 'drive.google.com') !== false) {
            // ลิงก์ Google Drive
            $video_link = str_replace("view?usp=sharing", "preview", $lesson['video_link']);
            echo '<iframe src="' . $video_link . '" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        } elseif (strpos($lesson['video_link'], 'dropbox.com') !== false) {
            // ลิงก์ Dropbox
            $video_link = str_replace("?dl=0", "?raw=1", $lesson['video_link']);
            echo '<iframe src="' . $video_link . '" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        } else {
            // ลิงก์อื่นๆ เช่นลิงก์ตรงหรือที่ไม่ใช่ Google Drive/Dropbox
            echo '<video src="' . $lesson['video_link'] . '" controls></video>';
        }
        ?>
        <div class="controls">
            <button class="play-pause" id="playPauseBtn"></button>
            <div class="progress-bar" id="progressBar">
                <div class="progress" id="progress"></div>
            </div>
            <div class="time" id="timeDisplay">00:00</div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>