<?php
session_start();

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "complaint_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect inputs safely
    $type       = $_POST["type"] ?? "N/A";
    $priority   = $_POST["priority"] ?? "N/A";
    $fullname   = $conn->real_escape_string($_POST["fullname"] ?? "");
    $email      = $conn->real_escape_string($_POST["email"] ?? "");
    $contact    = $conn->real_escape_string($_POST["contact"] ?? "");
    $os         = $conn->real_escape_string($_POST["os"] ?? "N/A");
    $software   = $conn->real_escape_string($_POST["software"] ?? "N/A");
    $error      = $conn->real_escape_string($_POST["error_details"] ?? "N/A");
    $device     = $conn->real_escape_string($_POST["device_type"] ?? "N/A");
    $serial     = $conn->real_escape_string($_POST["serial"] ?? "N/A");
    $hw_details = $conn->real_escape_string($_POST["hw_details"] ?? "N/A");
    $location   = $conn->real_escape_string($_POST["location"] ?? "N/A");
    $comments   = $conn->real_escape_string($_POST["comments"] ?? "N/A");
    $status     = $conn->real_escape_string($_POST["status"] ?? "New");

    // Handle file uploads
    $uploadedFiles = [];
    if (!empty($_FILES["attachments"]["name"][0])) {
        foreach ($_FILES["attachments"]["name"] as $key => $filename) {
            $tmp = $_FILES["attachments"]["tmp_name"][$key];
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $targetFile = $targetDir . time() . "_" . basename($filename);
            if (move_uploaded_file($tmp, $targetFile)) {
                $uploadedFiles[] = $targetFile;
            }
        }
    }
    $files = implode(", ", $uploadedFiles);

    // Insert into database
    $sql = "INSERT INTO complaints (type, priority, fullname, email, contact, os, software, error_details, device_type, serial, hw_details, location, comments, status, files)
        VALUES ('$type', '$priority', '$fullname', '$email', '$contact', '$os', '$software', '$error', '$device', '$serial', '$hw_details', '$location', '$comments', '$status', '$files')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION["complaint_id"] = $conn->insert_id;
        header("Location: success.php");
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>