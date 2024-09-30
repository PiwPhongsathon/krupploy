<?php
require_once 'vendor/autoload.php'; // โหลด Google API Client
session_start();

$client = new Google_Client();
$client->setClientId('353537036233-7racc66ugvr12l6na8iq0v3s1g7grjvc.apps.googleusercontent.com');  // ใส่ Client ID ที่ได้จาก Google Developer Console
$client->setClientSecret('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');  // ใส่ Client Secret ที่ได้จาก Google Developer Console
$client->setRedirectUri('http://localhost:8255/lerningwebsite/google-callback.php');
  // เปลี่ยนเป็น URL ของ google-callback.php
$client->addScope("email");
$client->addScope("profile");

// สร้างลิงก์สำหรับให้ผู้ใช้ล็อกอินด้วย Google
$loginUrl = $client->createAuthUrl();
header('Location: ' . $loginUrl);  // เปลี่ยนเส้นทางไปยัง Google เพื่อเข้าสู่ระบบ
exit();
?>
