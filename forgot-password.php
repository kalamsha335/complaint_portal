<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #66acea, #4b93a2);
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
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s;
        }
        input[type="email"]:focus {
            border-color: #667eea;
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
            transition: background 0.3s;
        }
        button:hover {
            background: #5646c2;
        }
        .message {
            margin-top: 15px;
            font-size: 14px;
        }
        .success { color: green; }
        .error { color: red; }
        a {
            display: inline-block;
            margin-top: 15px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover { text-decoration: underline; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Forgot Password 🔑</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="send_otp">Send OTP</button>
    </form>

    <div class="message">
    <?php
    if (isset($_POST['send_otp'])) {
        $email = $_POST['email'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result->num_rows > 0) {
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            $conn->query("UPDATE users SET otp='$otp', otp_expiry='$expiry' WHERE email='$email'");

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alkalamsha3325@gmail.com'; // <-- change this
                $mail->Password = 'ngxk soac fuoc cdvp';     // <-- paste 16-char app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('alkalamsha3325@gmail.com', 'Complaint Portal');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Password Reset OTP";
                $mail->Body = "Your OTP is <b>$otp</b>. It will expire in 10 minutes.";

                $mail->send();
                echo "<p class='success'>✅ OTP sent to your email.</p>";
                echo "<a href='verify_otp.php?email=$email'>Go to Verify OTP</a>";
            } catch (Exception $e) {
                echo "<p class='error'>❌ Email not sent. Error: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo "<p class='error'>❌ Email not found.</p>";
        }
    }
    ?>
    </div>
</div>
</body>
</html>