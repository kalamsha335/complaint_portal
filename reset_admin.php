<?php
// reset_admin.php  — run this once, then DELETE the file
$servername = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "login";

$conn = new mysqli($servername, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) { die("DB connect error: " . $conn->connect_error); }

$email = 'admin@email.com';
$newPlain = 'admin123';
$newHash = password_hash($newPlain, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $newHash, $email);
if ($stmt->execute()) {
    echo "Password updated. New password: $newPlain";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>