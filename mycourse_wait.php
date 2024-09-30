<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['userid']; // อ้างอิงจากนักเรียนที่เข้าสู่ระบบ

// ดึงข้อมูลคอร์สที่นักเรียนคนนี้ซื้อแล้ว
$query = "SELECT c.course_name, cp.purchase_date, cp.status, cp.confirmation_date 
          FROM course_purchases cp
          JOIN courses c ON cp.course_id = c.id
          WHERE cp.student_id = '$student_id'";
          



$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="stylesheet" href="./css/home.css">
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
            <li><a href="#learning-path"><img src="icon-path.svg" alt=""> คอร์สเรียนที่ลงทะเบียนแล้ว</a></li>
        </ul>
    </div>

    <div class="containner_program">
        <div class="">
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>คอร์สเรียนที่ซื้อแล้ว</h2>
                        <p>จำนวนคอร์สที่ซื้อทั้งหมด: <?php echo mysqli_num_rows($result); ?> คอร์ส</p>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>ชื่อคอร์ส</td>
                                <td>วันที่ซื้อ</td>
                                <td>สถานะ</td>
                                <td>วันที่ยืนยัน</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['purchase_date'])); ?></td>
                                        <td>
                                            <!-- ตรวจสอบสถานะและเพิ่มคลาส CSS ที่เหมาะสม -->
                                            <span class="status 
                                        <?php
                                        if ($row['status'] == 'ยืนยันแล้ว') {
                                            echo 'delivered'; // คลาสที่คุณใช้สำหรับ "ยืนยันแล้ว"
                                        } elseif ($row['status'] == 'รอตรวจสอบ') {
                                            echo 'pending'; // คลาสที่คุณใช้สำหรับ "รอตรวจสอบ"
                                        } elseif ($row['status'] == 'หมดอายุแล้ว') {
                                            echo 'return'; // คลาสที่คุณใช้สำหรับ "หมดอายุแล้ว"
                                        } else {
                                            echo 'inProgress'; // อื่นๆ เช่น กำลังดำเนินการ
                                        }
                                        ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['confirmation_date'] ? date('d/m/Y H:i', strtotime($row['confirmation_date'])) : '-'; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">คุณยังไม่ได้ซื้อคอร์สใด ๆ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>
</body>

</html>