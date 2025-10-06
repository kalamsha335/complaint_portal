<?php
// migrate_passwords.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $stored_password = $row['password'];

        // Check if password is already hashed
        if (password_get_info($stored_password)['algo'] === 0) {
            $hashed = password_hash($stored_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed, $id);
            $stmt->execute();
            $stmt->close();

            echo "✅ Password hashed for user ID $id<br>";
        } else {
            echo "🔒 Password already hashed for user ID $id<br>";
        }
    }
} else {
    echo "No users found.";
}

$conn->close();
?>
