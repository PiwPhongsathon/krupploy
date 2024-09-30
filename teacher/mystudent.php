<?php
session_start();
require_once "../components/database.php";

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

$user_id = $_SESSION['userid'];

// ดึงข้อมูลนักเรียนที่ลงทะเบียนในคอร์สของผู้ใช้ (ครู)
$students_query = "SELECT DISTINCT cp.student_id, CONCAT(u.fname, ' ', u.lname) AS username, u.email 
                   FROM user u 
                   INNER JOIN course_purchases cp ON u.id = cp.student_id 
                   INNER JOIN courses c ON c.id = cp.course_id 
                   WHERE c.user_id = '$user_id'";


$students_result = mysqli_query($conn, $students_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>

    <link rel="stylesheet" href="../css/teacher.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    <div class="containner_program">
        <div class="main-content">
            <div class="header">
                <h2>นักเรียนของฉัน</h2>
            </div>
            <div class="recentOrders">
                <div class="cardHeader">
                    <h4></h4>
                </div>
                <?php if (mysqli_num_rows($students_result) > 0): ?>


                    <table>
                        <thead>
                            <tr>
                                <td>ชื่อนักเรียน</td>
                                <td>อีเมล</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                <tr>
                                    <td><?= $student['username']; ?></td>
                                    <td><?= $student['email']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>คุณยังไม่มีนักเรียน</p>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <script src="../js/navbar.js"></script>
    <script src="../js/dropdownmenu.js"></script>
</body>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 16px;
        font-family: 'Mulish', sans-serif;
        text-align: left;
    }

    th,
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    thead tr {
        color: #333;
        text-align: left;
        font-weight: bold;
    }

    tbody tr {
        border-bottom: 1px solid #ddd;
    }

    tbody tr:nth-of-type(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    tbody tr td {
        color: #333;
    }
</style>

</html>