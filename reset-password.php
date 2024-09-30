<?php
$error_message = ""; // เพื่อเก็บข้อความข้อผิดพลาด
$email = ""; // เพื่อเก็บอีเมลของผู้ใช้

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ตรวจสอบว่ารหัสผ่านทั้งสองช่องตรงกันหรือไม่
    if ($password !== $confirm_password) {
        $error_message = "รหัสผ่านไม่ตรงกัน."; // เก็บข้อความข้อผิดพลาด
    } else {
        // ใช้ MD5 ในการแฮชรหัสผ่าน
        $new_password = md5($password);

        // Database connection
        require_once "components/database.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Validate the token using prepared statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE reset_token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $token_expiry = $row['token_expiry'];
            $email = $row['email']; // ดึงอีเมลจากฐานข้อมูล

            // Check if token has expired
            if (new DateTime() > new DateTime($token_expiry)) {
                header("Location: reset-password.php?error=Token has expired. Please request a new password reset link.");
                exit();
            } else {
                // Update the password using prepared statement
                $update_stmt = $conn->prepare("UPDATE user SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?");
                $update_stmt->bind_param("ss", $new_password, $token);
                if ($update_stmt->execute()) {
                    header("Location: reset-password.php?success=1"); // Redirect to reset_password page
                    exit();
                } else {
                    header("Location: reset-password.php?error=Error updating password.");
                    exit();
                }
                $update_stmt->close();
            }
        } else {
            header("Location: reset-password.php?error=Invalid token.");
            exit();
        }

        $stmt->close();
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่ม SweetAlert -->

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }

        body {
            font-family: "Noto Sans Thai";
            background-image: linear-gradient(#ED3E7D, #FCFF92);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        .login-header header {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }

        .login-header p {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 12px 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 30px;
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .input-field:focus {
            border-color: #9b59b6;
            outline: none;
            box-shadow: 0 6px 12px rgba(155, 89, 182, 0.4);
        }

        .input-submit {
            text-align: center;
        }

        .submit-btn {
            display: inline-block;
            background-color: #9F70FD;
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 14px 30px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 12px rgba(155, 89, 182, 0.4);
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background-color: #8e44ad;
            box-shadow: 0 8px 16px rgba(142, 68, 173, 0.6);
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            text-align: left;
            padding-left: 10px;
        }

        label {
            display: block;
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 10px;
            cursor: pointer;
        }

        .input-field::placeholder {
            color: #aaa;
        }

        @media (max-width: 500px) {
            .login {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login">
        <form action="reset-password.php" method="POST">
            <div class="login-header">
                <header>รีเซ็ตรหัสผ่าน</header>
                <p>ใส่รหัสผ่านใหม่ของคุณ</p>
            </div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>" required>
            <div class="input-box">
                <input type="password" class="input-field" name="password" placeholder="ใส่รหัสผ่านใหม่ของคุณ" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="confirm_password" id="confirm_password" placeholder="ยืนยัน รหัสผ่านใหม่ของคุณ" required>
                <?php if (!empty($error_message)): ?>
                    <small class="error-message"><?php echo $error_message; ?></small>
                <?php endif; ?>
            </div>
            <div class="input-submit">
                <button class="submit-btn" id="submit">เปลี่ยนรหัสผ่าน</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        <?php if (isset($_GET['success']) && $_GET['success'] == '1') : ?>
            swal("สำเร็จ!", "Your password has been reset successfully.", "success").then(() => {
                window.location.href = "index.php"; // กลับไปที่หน้า login หลังจากคลิก OK
            });
        <?php elseif (isset($_GET['error'])) : ?>
            swal("ข้อผิดพลาด!", "<?php echo $_GET['error']; ?>", "error").then(() => {
                window.location.href = "index.php";
            });
        <?php endif; ?>
    </script>
</body>



</html>