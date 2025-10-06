<?php
// signup.php

// Database connection details
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Message variable for feedback
$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<span style='color:red;'>Invalid email format.</span>";
    }
    elseif ($password !== $confirm_password) {
        $msg = "<span style='color:red;'>Passwords do not match.</span>";
    }
    elseif (strlen($password) < 6) {
        $msg = "<span style='color:red;'>Password must be at least 6 characters long.</span>";
    } 
    else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $msg = "<span style='color:red;'>This email is already registered.</span>";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                $msg = "<span style='color:green;'>✅ Registration successful! You can now <a href='login.php'>login</a>.</span>";
            } else {
                $msg = "<span style='color:red;'>Error: " . $stmt_insert->error . "</span>";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .signup-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .signup-box h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
            color: #333;
        }
        .signup-box input, .signup-box button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        .signup-box button {
            background-color: #007BFF;
            color: #fff;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        .signup-box button:hover {
            background-color: #0056c0;
        }
        .signup-box p {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="signup-box">
        <h2>Signup</h2>
        <form action="signup.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Re-enter Password" required>
            <button type="submit">Signup</button>
        </form>

        <!-- Show PHP message here -->
        <div class="message"><?php echo $msg; ?></div>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
