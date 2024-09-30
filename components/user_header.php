<?php

require_once "components/database.php";

// ตรวจสอบว่ามีการล็อกอินหรือไม่
if (isset($_SESSION['userid'])) {
    // ตรวจสอบว่า fname และ lname ยังไม่มีการกำหนดในเซสชันหรือไม่
    if (!isset($_SESSION['fname']) || !isset($_SESSION['lname'])) {
        // ดึงข้อมูลจากฐานข้อมูลตาม user_id
        $user_id = $_SESSION['userid'];
        $query = "SELECT fname, lname, profile_pic FROM user WHERE id = '$user_id'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['fname'] = $row['fname']; // กำหนดค่าลงในเซสชัน
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['profile_pic'] = !empty($row['profile_pic']) ? $row['profile_pic'] : 'pic-5.jpg';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styling for the form */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* ทำให้รูปโปรไฟล์เป็นวงกลม */
        .profile-pic {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            object-fit: cover;
        }
    </style>
</head>

<body>
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
                    <li><a href="./home.php">หน้าหลัก</a></li>
                    <li><a href="./course_login.php">คอร์สเรียน</a></li>
                    <li><a href="./program.php">โปรแกรมการสอน</a></li>
                    <li><a href="./infoteacherlogin.php">คุณครู</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button onclick="myFunction()" class="dropbtn">
                    <?php
                    // แสดงรูปโปรไฟล์จากเซสชัน
                    if (isset($_SESSION['profile_pic'])) {
                        echo '<img src="uploads/profiles/' . $_SESSION['profile_pic'] . '" alt="" class="profile-pic">';
                    } else {
                        // ถ้าไม่มีรูปโปรไฟล์ ใช้รูปภาพค่าเริ่มต้น
                        echo '<img src="pic-5.jpg" alt="" class="profile-pic">';
                    }
                    ?>
                    นักเรียน
                </button>
                <div id="myDropdown" class="dropdown-content">

                    <span id="firstName"><?php echo $_SESSION['fname'] ?></span>
                    <span id="lastName"><?php echo $_SESSION['lname'] ?></span>
                    <a href="#programpage">
                        <img src="img/program.png" alt="" width="20" height="20">โปรแกรมการสอน
                    </a>
                    <a href="javascript:void(0);" onclick="openModal()">
                        <img src="img/setting.png" alt="" width="25" height="25">ตั้งค่า
                    </a>
                    <a href="./logout.php">
                        <img src="img/logout.png" alt="">ออกจากระบบ
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Box -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>แก้ไขข้อมูลผู้ใช้</h2>
            <form action="update_user.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fname">ชื่อ:</label>
                    <input type="text" id="fname" name="fname" value="<?php echo isset($_SESSION['fname']) ? $_SESSION['fname'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="lname">นามสกุล:</label>
                    <input type="text" id="lname" name="lname" value="<?php echo isset($_SESSION['lname']) ? $_SESSION['lname'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password">รหัสผ่านใหม่:</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="profile_pic">อัปโหลดรูปภาพ:</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                </div>
                <button type="submit" class="btn-submit">บันทึก</button>
            </form>


        </div>
    </div>

    <script>
        // Script for opening modal
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        // Script for closing modal
        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Close modal when clicking outside of the modal
        window.onclick = function(event) {
            if (event.target == document.getElementById("myModal")) {
                closeModal();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Script for confirming form submission with SweetAlert
        document.querySelector("form").addEventListener("submit", function(e) {
            e.preventDefault(); // ป้องกันการ submit แบบปกติ

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "การกระทำนี้ไม่สามารถย้อนกลับได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, บันทึกเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // ส่งฟอร์มเมื่อผู้ใช้ยืนยัน
                }
            })
        });
    </script>
    <?php if (isset($_SESSION['success1']) || isset($_SESSION['error1'])): ?>
        <script>
            // ตรวจสอบว่ามีการตั้งค่าเซสชัน 'success' หรือ 'error' หรือไม่
            let success = "<?php echo isset($_SESSION['success1']) ? $_SESSION['success1'] : ''; ?>";
            let error = "<?php echo isset($_SESSION['error1']) ? $_SESSION['error1'] : ''; ?>";

            if (success) {
                Swal.fire('สำเร็จ', success, 'success1').then(() => {
                    window.location.reload(); // รีเฟรชหน้าหลังจากแสดงข้อความสำเร็จ
                });
            }

            if (error) {
                Swal.fire('ข้อผิดพลาด', error, 'error1').then(() => {
                    window.location.reload(); // รีเฟรชหน้าหลังจากแสดงข้อความข้อผิดพลาด
                });
            }
        </script>
        <?php
        unset($_SESSION['success1']);
        unset($_SESSION['error1']);
        ?>
    <?php endif; ?>
    <script src="js/navbar.js"></script>
</body>

</html>