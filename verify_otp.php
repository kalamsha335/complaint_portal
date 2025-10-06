<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4b93a2, #66acea);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        h2 { color: #333; margin-bottom: 20px; }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
        }
        button {
            width: 100%;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover { background: #5646c2; }
        .message { margin-top: 15px; font-size: 14px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Verify OTP 🔒</h2>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify">Verify</button>
    </form>

    <div class="message">
    <?php
    if (isset($_POST['verify'])) {
        $otp = trim($_POST['otp']);
        $email = isset($_GET['email']) ? $_GET['email'] : '';

        if (empty($email)) {
            echo "<p class='error'>⚠ Email missing in URL.</p>";
        } else {
            // Fetch OTP details from DB
            $query = $conn->query("SELECT otp, otp_expiry FROM users WHERE email='$email' LIMIT 1");

            if ($query && $query->num_rows > 0) {
                $row = $query->fetch_assoc();
                $db_otp = $row['otp'];
                $otp_expiry = $row['otp_expiry'];

                if ($db_otp === $otp) {
                    if (strtotime($otp_expiry) > time()) {
                        // OTP is correct and not expired
                        echo "<p class='success'>✅ OTP verified successfully.</p>";
                        echo "<a href='reset_password.php?email=$email'>Click here to reset your password</a>";

                        // Optional: clear OTP after success
                        $conn->query("UPDATE users SET otp=NULL, otp_expiry=NULL WHERE email='$email'");
                    } else {
                        echo "<p class='error'>⏰ OTP expired. Please request a new one.</p>";
                    }
                } else {
                    echo "<p class='error'>❌ Invalid OTP.</p>";
                }
            } else {
                echo "<p class='error'>⚠ Email not found.</p>";
            }
        }
    }
    ?>
    </div>
</div>
</body>
</html>