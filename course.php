<?php
session_start();
require_once "components/database.php";

// ดึงข้อมูลคอร์สพร้อมคะแนนเฉลี่ยจากฐานข้อมูล
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// ปรับ SQL ให้ดึงข้อมูลคอร์สพร้อมคะแนนเฉลี่ย
$sql = "
    SELECT c.*, COALESCE(AVG(r.rating), 0) AS average_rating, COUNT(r.rating) AS rating_count
    FROM courses c
    LEFT JOIN course_ratings r ON c.id = r.course_id
    WHERE 1=1
";

if ($category) {
    $sql .= " AND c.subject = '$category'";
}

if ($search) {
    $sql .= " AND (c.course_name LIKE '%$search%' OR c.course_content LIKE '%$search%')";
}

$sql .= " GROUP BY c.id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert -->
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
                    <li class="text-white"><a href="index.php">หน้าหลัก</a></li>
                    <li><a href="course.php">คอร์สเรียน</a></li>
                    <li><a href="#">โปรแกรมการสอน</a></li>
                    <li><a href="infoteacher.php">คุณครู</a></li>
                </ul>
            </div>
            <a href="index.php">
                <div class="signup_button">
                    เข้าสู่ระบบ/สมัครสมาชิก
                </div>
            </a>
        </div>
    </nav>

    <div class="containner_program">
        <div class="main-content">
            <h2>คอร์สเรียน</h2>
            <div class="inputcourse">
                <form action="course.php" method="GET">
                    <div class="search-bar">
                        <input type="text" class="search-input" placeholder="ค้นหาคอร์ส" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button type="submit" class="btn-search">ค้นหา</button>
                    </div>

                    <div class="category-section">
                        <div class="text-category">
                            <h3>หมวดหมู่</h3>
                        </div>
                        <div class="select-menu">
                            <select name="category" class="form-select">
                                <option value="">หมวดหมู่ทั้งหมด</option>
                                <option value="ภาษาอังกฤษ">ภาษาอังกฤษ</option>
                                <option value="ภาษาไทย">ภาษาไทย</option>
                                <option value="คณิตศาสตร์">คณิตศาสตร์</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-category mt-2">ค้นหาหมวดหมู่</button>
                    </div>
                </form>
                <p class="search-result">ผลการค้นหา (<?php echo $result->num_rows; ?>) คอร์ส</p>
            </div>
            <section class="dashboard-content">
                <?php
                if ($result->num_rows > 0) {
                    while ($course = $result->fetch_assoc()) {
                        // ดึงข้อมูลครูผู้สอน
                        $teacher_id = $course['user_id'];
                        $teacher_query = "SELECT fname, lname, bank_slip_image FROM user WHERE id = '$teacher_id'";
                        $teacher_result = mysqli_query($conn, $teacher_query);
                        $teacher = mysqli_fetch_assoc($teacher_result);
                        $teacher_name = $teacher['fname'] . ' ' . $teacher['lname'];
                        $bank_slip_image = $teacher['bank_slip_image'];
                ?>
                        <div class="boxcourse" onclick="location.href='course_infonologin.php?course_id=<?= $course['id']; ?>'">
                            <img src="uploads/covers/<?php echo $course['cover_image']; ?>" alt="course" class="course-image">
                            <div class="textboxcourse">
                                <p class="course-subject">วิชา <?php echo $course['subject']; ?></p>
                                <h5 class="course-title1"><?php echo $course['course_name']; ?></h5>
                                <p class="course-content1"><?php echo $course['course_content']; ?></p>
                                <div class="rate-price">
                                    <div>
                                        <!-- แสดงดาวที่ได้จากคะแนนเฉลี่ย -->
                                        <?php
                                        $average_rating = round($course['average_rating']);
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $average_rating) {
                                                echo '<span class="fa fa-star checked"></span>';
                                            } else {
                                                echo '<span class="fa fa-star"></span>';
                                            }
                                        }
                                        ?>
                                        <span>(<?php echo $course['rating_count']; ?>)</span>
                                    </div>
                                    <h4 class="price_course"><?php echo $course['price']; ?> บาท</h4>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>ไม่มีคอร์สที่พบ</p>";
                }
                ?>
            </section>
        </div>
    </div>

    <footer class="footer-07">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <h2 class="footer-heading"><a href="#" class="logo">KruPPloy</a></h2>
                    <p class="menu">
                        <a href="index.php">หน้าหลัก</a>
                        <a href="course.php">คอร์สเรียน</a>
                        <a href="#">โปรแกรมการสอน</a>
                        <a href="infoteacher.php">คุณครู</a>
                    </p>
                    <ul class="ftco-footer-social">
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook">
                                <span class="fab fa-facebook-f"></span>
                            </a>
                        </li>
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Line">
                                <span class="fab fa-line"></span> <!-- ใช้ไอคอน Line จาก Font Awesome -->
                            </a>
                        </li>
                        <li class="ftco-animate">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Instagram">
                                <span class="fab fa-instagram"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <p class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright ©<script>
                            document.write(new Date().getFullYear());
                        </script>2024 All rights reserved | This template is made with <i class="ion-ios-heart" aria-hidden="true"></i> by <a href="#" target="_blank">Colorlib.com</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="./js/navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>
</body>

</html>

<?php
$conn->close();
