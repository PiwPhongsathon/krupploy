<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// ฟังก์ชันสร้าง token
function generateToken()
{
    return bin2hex(random_bytes(16));
}

// เชื่อมต่อฐานข้อมูล
require_once "components/database.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ''; // ตัวแปรสำหรับเก็บข้อความ

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $userEmail = trim($_POST['email']); // ตรวจสอบอีเมลจากฟอร์ม

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $message = "ที่อยู่อีเมลไม่ถูกต้อง";
    } else {
        // สร้าง token และวันหมดอายุ (1 ชั่วโมงจากเวลาปัจจุบัน)
        $token = generateToken();
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // บันทึก token และวันหมดอายุในฐานข้อมูล
        $stmt = $conn->prepare("UPDATE user SET reset_token=?, token_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expiry, $userEmail);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';  // ใช้ SMTP ของ Gmail
                $mail->SMTPAuth   = true;
                $mail->Username   = 'phongsathonpiw@gmail.com';  // อีเมลของคุณ
                $mail->Password   = 'grui dfap cspu cynm';  // App password ของ Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // การเข้ารหัส TLS
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('phongsathonpiw@gmail.com', 'Reset Password KruPPloy');
                $mail->addAddress($userEmail);  // ส่งอีเมลไปยังที่อยู่ที่กรอก

                // Content
                $mail->isHTML(true);
                $resetLink = "http://localhost:8255/lerningwebsite/reset-password.php?token=" . $token; // สร้างลิงค์รีเซ็ตรหัสผ่าน
                $mail->Subject = 'Reset Password Website KruPPloy';
                $mail->Body    = 'กรุณาคลิกที่ลิงค์นี้เพื่อตั้งค่ารหัสผ่านใหม่: <a href="' . $resetLink . '">รีเซ็ตรหัสผ่าน</a>';
                $mail->AltBody = 'กรุณาคลิกที่ลิงค์นี้เพื่อตั้งค่ารหัสผ่านใหม่: ' . $resetLink;

                $mail->send();
                $message = 'Link รีเซ็ตรหัสผ่าน ได้ถูกส่งไปยังอีเมลของคุณแล้ว';
            } catch (Exception $e) {
                $message = "ไม่สามารถส่งข้อความได้. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "อีเมลนี้ไม่ถูกต้อง กรุณาใส่อีเมลใหม่";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert -->

</head>

<body>
    <div class="forgot-password-container">
        <div class="login-box">
            <h2>ลืมรหัสผ่าน</h2>
            <form action="forgot-password.php" method="post">
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="อีเมล" required>

                <button type="submit" name="submit" value="submit" class="login-button">ส่ง Link รีเซ็ตรหัสผ่าน</button>

                <div class="signup-link">
                    กลับไปที่ <a href="index.php">เข้าสู่ระบบ</a>
                </div>
            </form>
        </div>
    </div>
</body>

<style>
    body {
        font-family: "Noto Sans Thai";
        background-image: linear-gradient(#ED3E7D, #FCFF92);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .forgot-password-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 300px;
    }

    .login-box {
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="email"]:focus {
        border-color: #007bff;
        outline: none;
    }

    .login-button {
        background-color: #007bff;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .login-button:hover {
        background-color: #0056b3;
    }

    .signup-link {
        margin-top: 20px;
    }

    .signup-link a {
        color: #007bff;
        text-decoration: none;
    }

    .signup-link a:hover {
        text-decoration: underline;
    }
</style>

<script>
    // แสดง SweetAlert2 เมื่อมีข้อความ
    <?php if (!empty($message)) : ?>
        Swal.fire({
            title: 'แจ้งเตือน',
            text: "<?php echo $message; ?>",
            icon: 'info',
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>
</script>


</html>