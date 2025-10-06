<?php
session_start();

// 🚨 Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "complaint_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Fetch all complaints
$sql = "SELECT * FROM complaints ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f6f9;
      margin: 0;
      padding: 0;
    }
    header {
      background: linear-gradient(90deg, #FF9933, #138808);
      color: white;
      text-align: center;
      padding: 15px;
      font-size: 20px;
      font-weight: bold;
    }
    .container {
      width: 95%;
      margin: 30px auto;
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
      font-size: 14px;
    }
    th {
      background-color: #0d47a1;
      color: white;
    }
    tr:nth-child(even) {
      background: #f2f2f2;
    }
    .logout-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: #d32f2f;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: 0.3s;
    }
    .logout-btn:hover {
      background: #b71c1c;
    }
  </style>
</head>
<body>
  <header>Welcome Admin - <?php echo htmlspecialchars($_SESSION['username']); ?></header>

  <div class="container">
    <h2>All Complaints</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Priority</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Files</th>
        <th>Created At</th>
      </tr>
      <?php
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['type']}</td>
                      <td>{$row['priority']}</td>
                      <td>{$row['fullname']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['contact']}</td>
                      <td>{$row['status']}</td>
                      <td>";
                      if (!empty($row['files'])) {
                          $files = explode(',', $row['files']);
                          foreach ($files as $file) {
                              echo "<a href='$file' target='_blank'>View</a><br>";
                          }
                      } else {
                          echo "No files";
                      }
              echo "</td>
                    <td>{$row['created_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='9'>No complaints found</td></tr>";
      }
      ?>
    </table>

    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</body>
</html>
<?php $conn->close(); ?>
