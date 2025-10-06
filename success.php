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

// Get complaint ID from session 
$complaintId = $_SESSION["complaint_id"] ?? null;
$complaintDetails = null;

if ($complaintId) {
    $stmt = $conn->prepare("SELECT * FROM complaints WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $complaintId);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaintDetails = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Complaint Submitted</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    .card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 700px;
      width: 100%;
    }
    h1 {
      color: #2b6cb0;
      margin-bottom: 20px;
    }
    .details {
      text-align: left;
    }
    .details p {
      margin: 8px 0;
      padding: 6px 10px;
      background: #f9fafb;
      border-radius: 6px;
    }
    strong {
      color: #111827;
    }
    .btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: #2b6cb0;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
    }
    .btn:hover {
      background: #1a4d80;
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>✅ Complaint Submitted</h1>
    <?php if ($complaintDetails): ?>
      <div class="details">
        <?php foreach ($complaintDetails as $key => $value): ?>
          <p><strong><?php echo ucfirst(str_replace("_"," ",$key)); ?>:</strong> 
          <?php echo htmlspecialchars($value ?: "N/A"); ?></p>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No complaint details found.</p>
    <?php endif; ?>
    <a href="webdesign.php" class="btn">Go Back to Home</a>
  </div>
</body>
</html>
