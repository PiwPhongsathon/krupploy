<?php
session_start();
require_once "components/database.php";

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['userid'];

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // ดึงข้อมูลคอร์สจากฐานข้อมูลตาม course_id
    $query = "SELECT * FROM courses WHERE id = '$course_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "ไม่พบคอร์สที่คุณเลือก";
        exit();
    }

    // ดึงข้อมูลแบบฝึกหัดที่เชื่อมโยงกับคอร์สนี้
    $exercise_query = "SELECT * FROM exercises WHERE course_id = '$course_id' LIMIT 1"; // เราเลือกแค่แบบฝึกหัดแรก
    $exercise_result = mysqli_query($conn, $exercise_query);

    if ($exercise_result && mysqli_num_rows($exercise_result) > 0) {
        $exercise = mysqli_fetch_assoc($exercise_result);

        // ดึงผลการทำแบบฝึกหัดจากตาราง exercise_results
        $results_query = "SELECT * FROM exercise_results WHERE user_id = '$user_id' AND exercise_id = '" . $exercise['id'] . "' ORDER BY created_at DESC";
        $results_result = mysqli_query($conn, $results_query);
    } else {
        $exercise = null; // ถ้าไม่มีแบบฝึกหัดที่เกี่ยวข้องกับคอร์สนี้
    }

    // ดึงข้อมูลการให้ดาวของผู้ใช้กับคอร์สนี้
    $rating_query = "SELECT * FROM course_ratings WHERE user_id = '$user_id' AND course_id = '$course_id'";
    $rating_result = mysqli_query($conn, $rating_query);
    $user_rating = mysqli_fetch_assoc($rating_result); // การให้ดาวของผู้ใช้ในคอร์สนี้

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
        $rating = $_POST['rating'];

        if ($user_rating) {
            // ถ้าเคยให้ดาวแล้ว ให้ทำการอัปเดต
            $update_query = "UPDATE course_ratings SET rating = '$rating' WHERE user_id = '$user_id' AND course_id = '$course_id'";
            mysqli_query($conn, $update_query);
        } else {
            // ถ้ายังไม่เคยให้ ให้ทำการ insert
            $insert_query = "INSERT INTO course_ratings (user_id, course_id, rating) VALUES ('$user_id', '$course_id', '$rating')";
            mysqli_query($conn, $insert_query);
        }

        // อัปเดตผลการให้ดาวใหม่
        header("Location: course_detail.php?course_id=$course_id");
        exit();
    }
} else {
    echo "ไม่มีการส่งค่า course_id มา";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดคอร์ส - KruPPloy</title>
    <link rel="stylesheet" href="./css/home.css">
    <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            /* เพื่อจัดเรียงดาวจากขวาไปซ้าย */
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }

        .rating input:checked~label,
        .rating input:checked~label~label {
            color: #ffc700;
            /* สีของดาวที่ถูกเลือก */
        }

        .rating label:hover,
        .rating label:hover~label {
            color: #deb217;
            /* สีของดาวเมื่อ hover */
        }
    </style>
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
                    <div class="image">
                        <img src="uploads/covers/<?php echo $course['cover_image']; ?>" alt="course image" class="course-cover-image">
                    </div>
                    <div class="view-video-button">
                    <a href="watch_video.php?course_id=<?php echo $course['id']; ?>">
                            <button>ดูวิดีโอ</button>
                        </a>
                    </div>
                </div>

                <div class="course-details1">
                    <h4 class="course-title1"><?php echo $course['course_name']; ?></h4>
                    <p class="course-subject1">วิชา: <?php echo $course['subject']; ?></p>
                    <p class="course-description1"><?php echo $course['course_content']; ?></p>
                </div>
            </section>
        </div>

        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h3>ผลการทำแบบฝึกหัด</h3>
                    <a href="take_exercise.php?exercise_id=<?php echo $exercise['id']; ?>" class="exercise-btn">ทำแบบฝึกหัด</a>
                </div>
                <div class="exercise-section">
                    <?php if ($exercise): ?>

                        <?php if ($results_result && mysqli_num_rows($results_result) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <td>วันที่ทำแบบฝึกหัด</td>
                                        <td>คะแนนที่ได้</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($result = mysqli_fetch_assoc($results_result)): ?>
                                        <tr>
                                            <td><?php echo $result['created_at']; ?></td>
                                            <td><?php echo $result['score']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="no-exercise-message">มีแบบฝึกหัดให้ทำอยู่นะ เรียนเสร็จแล้วอย่าลืมทำน้า</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="no-exercise-message">ไม่มีแบบฝึกหัดสำหรับคอร์สนี้</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="details">
            <div class="recentOrders">
                <div class="rating-section">
                    <h3>ให้คะแนนคอร์สนี้</h3>
                    <form action="" method="POST">
                        <div class="rating">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php if ($user_rating && $user_rating['rating'] == $i) echo 'checked'; ?> />
                                <label for="star<?php echo $i; ?>">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 custom-btn">บันทึกคะแนน</button>
                    </form>
                </div>
            </div>
        </div>

    </div>





    <script src="navbar.js"></script>
    <script src="./js/dropdownmenu.js"></script>


</body>

</html>

<?php
$conn->close();
?>