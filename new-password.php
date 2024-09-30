<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // แสดงฟอร์มให้ผู้ใช้กรอกรหัสผ่านใหม่
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
</head>
<body>
    <h2>กรอกรหัสผ่านใหม่</h2>
    <form action="update-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="new-password">รหัสผ่านใหม่:</label>
        <input type="password" id="new-password" name="new-password" required>
        <button type="submit">อัปเดตรหัสผ่าน</button>
    </form>
</body>
</html>
<?php
}
?>