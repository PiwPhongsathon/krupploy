<?php

session_start();
require_once "components/database.php";



if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

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

if (isset($_SESSION['registration_message'])) {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'คุณได้ส่งข้อมูลแล้ว',
            text: '{$_SESSION['registration_message']}',
            showConfirmButton: true
        });
    </script>";
    unset($_SESSION['registration_message']); // ลบข้อความออกจากเซสชัน
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
            <div class="header">
                <h2>คุณครู</h2>
                <div class="btn-group">
                    <button type="button" class="btn-addteacher btn">
                        <i class="fas fa-user"></i> ลงทะเบียนคุณครู
                    </button>
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


    <script>
        document.querySelector('.btn-addteacher').addEventListener('click', function(event) {
            event.preventDefault(); // ป้องกันการเปลี่ยนหน้าโดยตรง

            // ส่งคำขอ Ajax เพื่อเช็คข้อมูลครู
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_teacher_registration.php', true); // ไฟล์ PHP สำหรับเช็คข้อมูล
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.exists) {
                        // ถ้ามีข้อมูลอยู่แล้วให้แสดง SweetAlert
                        Swal.fire({
                            icon: 'warning',
                            title: 'คุณลงทะเบียนไปแล้ว',
                            text: 'กำลังรอตรวจสอบ',
                            showConfirmButton: true
                        });
                    } else {
                        // ถ้าไม่มีข้อมูลให้ไปที่หน้า addteacher.php
                        window.location.href = 'addteacher.php';
                    }
                }
            };

            xhr.send();
        });
    </script>

    <?php include './components/footeruser.php'; ?>

    <script src="./js/navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>



</body>

</html>
<?php
$conn->close();
?>