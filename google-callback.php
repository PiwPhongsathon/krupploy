<?php
require_once 'vendor/autoload.php'; // โหลด Google API Client

session_start();

$client = new Google_Client();
$client->setClientId('353537036233-7racc66ugvr12l6na8iq0v3s1g7grjvc.apps.googleusercontent.com');  // ใส่ Client ID ที่ได้จาก Google Developer Console
$client->setClientSecret('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');  // ใส่ Client Secret ที่ได้จาก Google Developer Console
$client->setRedirectUri('http://localhost:8255/lerningwebsite/google-callback.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    // แลกเปลี่ยน code เป็น access token และตรวจสอบ token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        // จัดการข้อผิดพลาด
        echo 'Error occurred: ' . $token['error'];
        exit();
    }

    $client->setAccessToken($token['access_token']);

    // ดึงข้อมูลโปรไฟล์จาก Google
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;

    // ตรวจสอบและบันทึกข้อมูลผู้ใช้ลงในฐานข้อมูลด้วย prepared statement
    require_once 'components/database.php';

    // ตรวจสอบว่าผู้ใช้มีอยู่แล้วในระบบหรือไม่
    $stmt = $conn->prepare("SELECT id, role FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        // ถ้าผู้ใช้ยังไม่มีในระบบ ให้บันทึกลงฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO user (email, fname, lname, role) VALUES (?, ?, '', 'student')");
        $stmt->bind_param('ss', $email, $name);
        $stmt->execute();
        $userid = $stmt->insert_id;
        $role = 'student';
    } else {
        $userid = $user['id'];
        $role = $user['role'];
    }

    // จัดเก็บข้อมูลลง session
    $_SESSION['userid'] = $userid;
    $_SESSION['user'] = $name;
    $_SESSION['role'] = $role;

    // เปลี่ยนเส้นทางตามบทบาทของผู้ใช้
    if ($role === 'teacher') {
        header("Location: teacher/home.php");
    } else {
        header("Location: home.php");
    }
    
    exit();
} else {
    echo "Error: No authorization code received.";
}
?>