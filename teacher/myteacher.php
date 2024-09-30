<?php


session_start();
require_once "../components/database.php";
date_default_timezone_set('Asia/Bangkok');


$allowedEmail = "admin1@gmail.com";
// ตรวจสอบว่าผู้ใช้ได้ล็อกอินแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php"); // ถ้ายังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}


$userId = $_SESSION['userid'];
$sql = "SELECT email FROM user WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row || $row['email'] !== $allowedEmail) {
    header("Location: program.php"); // หากอีเมลไม่ตรงกัน ให้เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}
// ตรวจสอบการเรียกใช้ AJAX เพื่อดึงข้อมูล
if (isset($_POST['action']) && $_POST['action'] == 'viewTeacher' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // ดึงข้อมูลจาก teacher_profiles และ users
    $sql = "SELECT tp.nickname, tp.full_name, tp.faculty, tp.university, tp.created_at, tp.status, u.profile_pic, tp.confirmed_at
        FROM teacher_profiles tp
        JOIN user u ON tp.user_id = u.id
        WHERE tp.user_id = $user_id";


    $result = mysqli_query($conn, $sql);

    // ตรวจสอบผลลัพธ์
    if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode($row); // ส่งข้อมูลกลับในรูป JSON
        } else {
            echo json_encode(['error' => 'ไม่พบข้อมูล']); // ไม่มีข้อมูลตรงนี้
        }
    } else {
        echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]); // ตรวจสอบว่า query ล้มเหลวหรือไม่
    }
    exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'deleteTeacher' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // สร้างคำสั่ง SQL สำหรับการลบ
    $sql = "DELETE FROM teacher_profiles WHERE user_id = $user_id";

    // ตรวจสอบว่าการลบสำเร็จหรือไม่
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]); // ส่งผลลัพธ์กลับเป็น JSON
    } else {
        echo json_encode(['success' => false, 'error' => 'ล้มเหลวในการลบข้อมูล: ' . mysqli_error($conn)]);
    }
    exit();
}


if (isset($_POST['action']) && $_POST['action'] == 'confirmTeacher' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $confirmed_at = date('Y-m-d H:i:s'); // เวลาปัจจุบัน

    // สร้างคำสั่ง SQL สำหรับการอัพเดตสถานะ
    $sql = "UPDATE teacher_profiles SET status = 'ยืนยันแล้ว', confirmed_at = '$confirmed_at' WHERE user_id = $user_id";

    // อัปเดต role ในตาราง user
    $updateUserRoleSql = "UPDATE user SET role = 'teacher' WHERE id = $user_id";

    // เริ่มต้นการทำงานในฐานข้อมูล
    mysqli_begin_transaction($conn);

    try {
        // ทำการอัปเดตสถานะของคุณครู
        if (mysqli_query($conn, $sql)) {
            // ทำการอัปเดต role ของผู้ใช้
            if (mysqli_query($conn, $updateUserRoleSql)) {
                mysqli_commit($conn); // ถ้าทั้งสองสำเร็จ ให้บันทึกการเปลี่ยนแปลง
                echo json_encode(['success' => true]); // ส่งผลลัพธ์กลับเป็น JSON
            } else {
                throw new Exception('ล้มเหลวในการอัปเดต role: ' . mysqli_error($conn));
            }
        } else {
            throw new Exception('ล้มเหลวในการอัปเดตข้อมูล: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        mysqli_rollback($conn); // ถ้ามีข้อผิดพลาด ให้ยกเลิกการเปลี่ยนแปลง
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit();
}

// ดึงข้อมูลการลงทะเบียนคุณครูจากฐานข้อมูลเพื่อแสดงในตาราง
$sql = "SELECT nickname, full_name, faculty, university, created_at, status, confirmed_at, user_id FROM teacher_profiles";

$result = mysqli_query($conn, $sql);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200;800&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
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
        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>คุณครูที่ลงทะเบียน</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>ชื่อจริง</td>
                            <td>วันที่ลงทะเบียน</td>
                            <td>สถานะ</td>
                            <td>วันที่ยืนยัน</td>
                            <td>การจัดการ</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <span class="status 
                    <?php
                                if ($row['status'] == 'ยืนยันแล้ว') {
                                    echo 'delivered';
                                } elseif ($row['status'] == 'รอตรวจสอบ') {
                                    echo 'pending';
                                }
                    ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($row['confirmed_at']) && $row['status'] == 'ยืนยันแล้ว') {
                                            echo date('d/m/Y H:i', strtotime($row['confirmed_at']));
                                        } else {
                                            echo 'ยังไม่มีการยืนยัน';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-view" onclick="viewTeacher(<?php echo $row['user_id']; ?>)">
                                                <i class="fas fa-eye"></i> ตรวจสอบ
                                            </button>
                                            <?php if ($row['status'] == 'รอตรวจสอบ') { ?>
                                                <button class="btn-confirm" onclick="confirmTeacher(<?php echo $row['user_id']; ?>)">
                                                    <i class="fas fa-check"></i> ยืนยัน
                                                </button>
                                                <button class="btn-delete" onclick="deleteTeacher(<?php echo $row['user_id']; ?>)">
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">ยังไม่มีการลงทะเบียนคุณครู</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="teacherModal" class="modal" style="display: none;"> <!-- ซ่อน modal โดยเริ่มต้น -->
        <div class="modal-content">
            <h4>รายละเอียดคุณครู</h4>
            <div class="modal-body">
                <div class="modal-image">
                    <img id="modalProfilePicture" alt="Profile Picture">
                </div>
                <div class="modal-details">
                    <p><strong>ชื่อ:</strong> <span id="modalFullName"></span></p>
                    <p><strong>คณะ:</strong> <span id="modalFaculty"></span></p>
                    <p><strong>มหาวิทยาลัย:</strong> <span id="modalUniversity"></span></p>
                    <p><strong>วันที่ลงทะเบียน:</strong> <span id="modalCreatedAt"></span></p>
                    <p><strong>สถานะ:</strong> <span id="modalStatus"></span></p>
                </div>

            </div>
            <div class="modal-footer">
                <button id="closeModal" class="modal-close">ปิด</button>
            </div>
        </div>

    </div>

    <script>
        function viewTeacher(userId) {
            console.log("Sending request for user_id:", userId); // ตรวจสอบว่าฟังก์ชันถูกเรียกใช้หรือไม่
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "myteacher.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Response received:", xhr.responseText);
                    try {
                        const teacher = JSON.parse(xhr.responseText);
                        if (!teacher.error) {
                            // ใส่ข้อมูลใน modal
                            document.getElementById('modalProfilePicture').src = "../uploads/profiles/" + teacher.profile_pic;
                            document.getElementById('modalFullName').textContent = teacher.full_name;
                            document.getElementById('modalFaculty').textContent = teacher.faculty;
                            document.getElementById('modalUniversity').textContent = teacher.university;
                            document.getElementById('modalCreatedAt').textContent = teacher.created_at;
                            document.getElementById('modalStatus').textContent = teacher.status;


                            // เปิด modal
                            document.getElementById('teacherModal').style.display = 'block';
                        } else {
                            alert(teacher.error); // แสดงข้อความข้อผิดพลาดที่มาจาก PHP
                        }
                    } catch (e) {
                        console.error("Failed to parse JSON:", e);
                    }
                } else {
                    console.error("Error in AJAX request:", xhr.statusText);
                }
            };
            xhr.send("action=viewTeacher&user_id=" + userId);
        }

        // ฟังก์ชันสำหรับปิด modal
        document.getElementById('closeModal').onclick = function() {
            document.getElementById('teacherModal').style.display = 'none';
        };
    </script>

    <script>
        function deleteTeacher(userId) {
            Swal.fire({
                title: 'ยืนยันการลบ',
                text: "คุณต้องการลบคุณครูนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "myteacher.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                Swal.fire(
                                    'ลบสำเร็จ!',
                                    'คุณครูถูกลบเรียบร้อยแล้ว.',
                                    'success'
                                ).then(() => {
                                    location.reload(); // รีเฟรชหน้าเพื่อตรวจสอบการเปลี่ยนแปลง
                                });
                            } else {
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    response.error,
                                    'error'
                                );
                            }
                        }
                    };
                    xhr.send("action=deleteTeacher&user_id=" + userId);
                }
            });
        }
    </script>

    <script>
        function confirmTeacher(userId) {
            Swal.fire({
                title: 'ยืนยันการยืนยัน',
                text: "คุณต้องการยืนยันคุณครูนี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "myteacher.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                Swal.fire(
                                    'ยืนยันสำเร็จ!',
                                    'คุณครูถูกยืนยันเรียบร้อยแล้ว.',
                                    'success'
                                ).then(() => {
                                    location.reload(); // รีเฟรชหน้าเพื่อตรวจสอบการเปลี่ยนแปลง
                                });
                            } else {
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    response.error,
                                    'error'
                                );
                            }
                        }
                    };
                    xhr.send("action=confirmTeacher&user_id=" + userId);
                }
            });
        }
    </script>

    <script src="../js/navbar.js"></script>
    <script src="../js/dropdownmenu.js"></script>
</body>

</html>