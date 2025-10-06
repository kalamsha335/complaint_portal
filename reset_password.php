<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {font-family:'Segoe UI';background:linear-gradient(135deg,#ff9966,#ff5e62);display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
        .container {background:#fff;padding:30px 40px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.2);width:350px;text-align:center;}
        input,button {width:100%;padding:12px;margin:10px 0;border-radius:8px;border:1px solid #ccc;}
        button {background:#ff5e62;color:#fff;border:none;font-weight:bold;}
        .success{color:green}.error{color:red}
    </style>
</head>
<body>
<div class="container">
    <h2>Reset Password 🔄</h2>
    <form method="POST">
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
    <div class="message">
    <?php
    if (isset($_POST['reset'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $update = $conn->query("UPDATE users SET password='$newPassword', otp=NULL, otp_expiry=NULL WHERE email='$email'");
        if ($update) {
            echo "<p class='success'>✅ Password reset successful! You can now <a href='login.php'>login</a>.</p>";
        } else {
            echo "<p class='error'>❌ Something went wrong. Try again.</p>";
        }
    }
    ?>
    </div>
</div>
</body>
</html>