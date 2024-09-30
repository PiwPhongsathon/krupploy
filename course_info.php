<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่ามีการส่ง course_id มาหรือไม่
if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];
    $userId = $_SESSION['userid']; // สมมติว่าคุณเก็บ user id ไว้ใน session หลังจากผู้ใช้เข้าสู่ระบบ

    // กำหนดค่าเริ่มต้นให้กับตัวแปร $hasPurchased
    $hasPurchased = false;

    // ตรวจสอบว่าผู้ใช้ได้ซื้อคอร์สนี้ไปแล้วหรือยัง
    $purchase_query = "SELECT * FROM course_purchases WHERE course_id = $courseId AND student_id = $userId";
    $purchase_result = $conn->query($purchase_query);

    if ($purchase_result->num_rows > 0) {
        $hasPurchased = true; // ถ้ามีข้อมูลการซื้อ ให้กำหนดเป็น true
    }

    // ดึงข้อมูลคอร์สจากฐานข้อมูลโดยใช้ course_id
    $sql = "SELECT * FROM courses WHERE id = $courseId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        // ดึงข้อมูล bank slip image
        $teacher_id = $course['user_id'];
        $teacher_query = "SELECT fname, lname, profile_pic,bank_slip_image FROM user WHERE id = '$teacher_id'";
        $teacher_result = mysqli_query($conn, $teacher_query);
        $teacher = mysqli_fetch_assoc($teacher_result);
        $teacher_name = $teacher['fname'] . ' ' . $teacher['lname'];
        $bank_slip_image = $teacher['bank_slip_image'];
        $profile_image = $teacher['profile_pic']; // ใช้รูปโปรไฟล์
    } else {
        echo "ไม่พบข้อมูลคอร์ส";
        exit;
    }

    if (isset($_SESSION['success']) && $_SESSION['success']) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'คุณได้ซื้อคอร์สเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        </script>";
        unset($_SESSION['success']);
    }

    // กรณีเกิดข้อผิดพลาด
    if (isset($_SESSION['error']) && $_SESSION['error']) {
        echo "<script>
            Swal.fire({
                title: 'ผิดพลาด!',
                text: 'การซื้อคอร์สล้มเหลว กรุณาลองใหม่อีกครั้ง',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        </script>";
        unset($_SESSION['error']);
    }
} else {
    echo "ไม่มีคอร์สที่เลือก";
    exit;
}

?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert -->
    <style>
        .back-button {
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #ff4c4c;
        }

        .buy-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buy-button:hover {
            background-color: #218838;
        }

        .modal-body img {
            width: 100%;
        }

        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .teacher-image-container {
            margin-top: 20px;
            text-align: center;
        }

        .teacher-image-small {
            width: 35px;
            /* ขนาดที่เล็กลง */
            height: auto;
            border-radius: 50%;
            vertical-align: middle;
            /* จัดแนวกลางระหว่างข้อความกับรูปภาพ */
            margin-left: 5px;
            /* ระยะห่างระหว่างรูปกับคำ */
        }
    </style>
</head>

<body>
    <?php include './components/user_header.php'; ?>

    <!-- ปุ่มย้อนกลับ -->
    <div class="container mt-4">
        <button class="back-button" onclick="goBack()">ย้อนกลับ</button>
    </div>

    <div class="containner_program">
        <div class="main-content">
            <section class="watch-video">
                <div class="video-container">
                    <div class="image">
                        <img src="uploads/covers/<?php echo $course['cover_image']; ?>" alt="course image" class="course-cover-image">
                    </div>
                </div>

                <div class="course-details1">
                    <h4 class="course-title1"><?php echo $course['course_name']; ?></h4>
                    <p class="course-subject1">วิชา: <?php echo $course['subject']; ?></p>
                    <div class="course-content1">
                        <p>สอนโดย: <img src="uploads/profiles/<?php echo $profile_image; ?>" alt="Teacher Profile Image" class="teacher-image-small" /> <?php echo $teacher_name; ?></p>
                    </div>

                    <p class="course-description1"><?php echo $course['course_content']; ?></p>
                    <h4 class="price_course1"><?php echo $course['price']; ?> บาท</h4>

                    <!-- ปุ่มซื้อคอร์ส -->
                    <?php if ($hasPurchased): ?>
                        <!-- แสดง SweetAlert หากผู้ใช้ได้ซื้อคอร์สนี้ไปแล้ว -->
                        <button type="button" class="btn btn-primary" onclick="showAlreadyPurchasedAlert()">ซื้อคอร์สนี้</button>
                    <?php else: ?>
                        <!-- แสดง Modal หากผู้ใช้ยังไม่ได้ซื้อคอร์ส -->
                        <button type="button" class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#buyCourseModal"
                            data-bank-slip="uploads/bank_slips/<?php echo $bank_slip_image; ?>">
                            ซื้อคอร์สนี้
                        </button>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Modal สำหรับการซื้อคอร์ส -->
        <div class="modal fade" id="buyCourseModal" tabindex="-1" aria-labelledby="buyCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buyCourseModalLabel">การชำระเงินสำหรับคอร์ส: <?php echo $course['course_name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>กรุณาชำระเงินจาก QR CODE ด้านล่างนี้</p>
                        <img src="" id="modal-bank-slip" alt="Bank Slip Image" /> <!-- เพิ่ม id ให้รูปภาพ -->

                        <p>หลังจากทำการโอนเงินแล้ว โปรดแนบสลิปเพื่อยืนยันการซื้อคอร์ส แล้วรอคุณครูตรวจสอบ</p>

                        <!-- ฟอร์มอัปโหลดสลิป -->
                        <form id="slipForm" action="components/submit_slip.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="slipImage" class="form-label">แนบสลิปการโอนเงิน</label>
                                <input class="form-control" type="file" name="slip_image" id="slipImage" required>
                            </div>
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="button" class="btn btn-success" onclick="submitSlip()">ยืนยันการโอนเงิน</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function goBack() {
                window.history.back();
            }

            // แสดง SweetAlert ถ้าผู้ใช้ได้ซื้อคอร์สนี้ไปแล้ว
            function showAlreadyPurchasedAlert() {
                Swal.fire({
                    title: 'คุณได้ซื้อคอร์สนี้ไปแล้ว!',
                    text: 'คุณสามารถตรวจสอบได้ที่ โปรแกรมการสอน',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง'
                });
            }

            function submitSlip() {
                var formData = new FormData(document.getElementById('slipForm'));

                fetch('components/submit_slip.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: 'คุณได้ส่งสลิปเรียบร้อยแล้ว',
                                icon: 'success',
                                confirmButtonText: 'ตกลง'
                            }).then(() => {
                                window.location.href = 'course_login.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถทำการส่งได้',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    });
            }

            var staticBackdrop = document.getElementById('buyCourseModal');
            staticBackdrop.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var bankSlip = button.getAttribute('data-bank-slip'); // ดึงค่าลิงก์สลิปจากปุ่มที่กด

                // อัปเดต src ของภาพใน modal
                var modalBankSlipImage = staticBackdrop.querySelector('#modal-bank-slip');
                modalBankSlipImage.src = bankSlip;
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="navbar.js"></script>
        <script src="js/dropdownmenu.js"></script>
</body>

</html>

<?php
$conn->close();
?>