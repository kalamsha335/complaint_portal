<?php
session_start();

// Database connection (keep your own)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "login";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

$error = "";

// Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, full_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id']  = $id;
            $_SESSION['username'] = $full_name;
            $_SESSION['role']     = $role;

            // ✅ Redirect based on role
            if ($role === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: webdesign.php"); // your user home page
            }
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NNTPPS Login</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      font-family: Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
      position: relative;
      overflow: hidden;
    }
    body::before {
      content: "";
      position: absolute;
      width: 280px;
      height: 280px;
      background: url("https://as2.ftcdn.net/v2/jpg/05/26/36/17/1000_F_526361730_Ige2xrRZMkWpdQqqhfj48ZdieYB3bx5y.jpg") no-repeat center center;
      background-size: contain;
      opacity: 60%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 0;
    }
    .logo { position: absolute; top: 15px; left: 20px; width: 80px; height: 80px; z-index: 10; }
    .login-container {
      background: rgba(255, 255, 255, 0.95);
      max-width: 360px;
      width: 100%;
      padding: 35px 28px;
      border-radius: 14px;
      box-shadow: 0 6px 28px rgba(0,0,0,0.25);
      border: 4px solid;
      border-image: linear-gradient(90deg, #FF9933, #ffffff, #138808) 1;
      position: relative;
      z-index: 1;
    }
    .login-container h2 { text-align: center; margin-bottom: 28px; color: #0d47a1; font-weight: bold; }
    .login-container label { display:block; margin-bottom:7px; color:#222; font-weight:500; }
    .login-container input[type="email"], .login-container input[type="password"] {
      width: 100%; padding: 10px; margin-bottom: 20px;
      border: 1px solid #bdbdbd; border-radius: 5px;
      font-size: 15px; background: #f9f9f9;
    }
    .login-container input:focus { border: 1.5px solid #1976d2; outline: none; }
    .login-container button {
      width: 100%; padding: 12px;
      background: linear-gradient(90deg, #FF9933 0%, #138808 100%);
      color: #fff; border: none; border-radius: 6px;
      font-size: 17px; font-weight: bold; cursor: pointer;
      transition: all 0.3s ease-in-out;
    }
    .login-container button:hover {
      box-shadow: 0 0 18px rgba(255,153,51,0.7), 0 0 18px rgba(19,136,8,0.7);
      transform: scale(1.03);
    }
    .error-message { color: red; text-align: center; margin-top: 10px; }

    /* --- TOP MENU STYLING --- */
    .menu ul {
      margin: 0;
      padding: 0;
      list-style: none;
      display: flex;
      gap: 18px;
      align-items: center;
    }
    .menu ul li {
      position: relative;
    }
    .menu ul li a {
      text-decoration: none;
      color: #0d47a1;
      font-weight: bold;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.25s ease-in-out;
    }
    .menu ul li a:hover {
      background: #e3f2fd;
      color: #1976d2;
    }
    /* Dropdowns */
    #overviewDropdown, #aboutUsDropdown {
      display: none;
      position: absolute;
      top: 42px;
      left: 0;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
      padding: 8px 0;
      min-width: 220px;
      z-index: 100;
    }
    #overviewDropdown li, 
    #aboutUsDropdown li {
      padding: 0;
    }
    #overviewDropdown li a, 
    #aboutUsDropdown li a {
      display: block;
      padding: 10px 16px;
      color: #333;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.25s;
    }
    #overviewDropdown li a:hover, 
    #aboutUsDropdown li a:hover {
      background: #e3f2fd;
      color: #1976d2;
      padding-left: 20px;
    }
    /* Submenu */
    #aboutUsDropdown {
      top: 0;
      left: 230px;
    }
  </style>
</head>
<body>
  <!-- Logo -->
  <img src="https://tse4.mm.bing.net/th/id/OIP.474dIxIZWu7ko6A7PZo4DAHaHV?rs=1&pid=ImgDetMain&o=7&rm=3" alt="NLC Logo" class="logo">
  <div style="position: absolute; top: 100px; left: 20px; z-index: 10; width:140px; text-align:left;">
    <span style="font-size:12px; color:#1976d2; font-weight:500;">CREATING WEALTH</span><br>
    <span style="font-size:12px; color:#1976d2; font-weight:500;">FOR WELLBEING</span>
  </div>

  <!-- Login Box -->
  <div class="login-container">
    <h2>NNTPPS Login</h2>
    <form method="POST" action="">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div style="display:flex; justify-content:space-between; align-items:center; margin-top:4px; margin-bottom:8px;">
        <label style="display:flex; align-items:center; font-size:13px;">
          <input type="checkbox" id="rememberMe" style="width:13px; height:13px; margin-right:4px;">
          Remember Me
        </label>
        <a href="forgot-password.php" style="color:#1976d2; font-size:13px; text-decoration:underline;">Forgot Password?</a>
      </div>
      <button type="submit">Login</button>
      <?php if (!empty($error)) { ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php } ?>
    </form>
    <div style="margin-top:18px; text-align:left;">
      <span style="font-size:14px; color:#333;">
        Don't have an account? 
        <a href="signup.php" style="color:#1976d2; text-decoration:underline;">Register</a>
      </span>
    </div>
  </div>
  
  <!-- Navigation Menu -->
  <div class="menu" style="position: absolute; top: 25px; right: 30px; z-index: 20;">
    <ul>
      <li><a href="https://www.nlcindia.in/website/en/">Home</a></li>
      <li>
        <a href="#" id="overviewBtn">Overview ▼</a>
        <ul id="overviewDropdown">
          <li>
            <a href="#" id="aboutUsBtn">About Us ►</a>
            <ul id="aboutUsDropdown">
              <li><a href="https://www.nlcindia.in/website/en/aboutus/vision.html" target="_blank">Vision, Mission &amp; Core Value</a></li>
              <li><a href="https://www.nlcindia.in/website/en/aboutus/aboutnlc.html" target="_blank">About NLCIL</a></li>
              <li><a href="https://www.nlcindia.in/website/en/aboutus/history.html" target="_blank">NLCIL History</a></li>
              <li><a href="https://www.nlcindia.in/website/en/aboutus/ourjourney.html" target="_blank">Our Journey</a></li>
              <li><a href="https://www.nlcindia.in/website/en/aboutus/nlcilprojects/currentprojects.html" target="_blank">NLCIL Projects</a></li>
            </ul>
          </li>
          <li><a href="https://www.nlcindia.in/website/en/aboutus/boardofdirectors.html" target="_blank">Board of Directors</a></li>
          <li><a href="https://www.nlcindia.in/website/en/aboutus/generalinformation/home.html" target="_blank">General Information</a></li>
          <li><a href="https://www.nlcindia.in/website/en/aboutus/perfomance.html" target="_blank">Performance</a></li>
          <li><a href="https://www.nlcindia.in/website/en/aboutus/corporateprofile.html" target="_blank">Corporate Profile</a></li>
        </ul>
      </li>
      <li><a href="https://www.nlcindia.in/website/en/sustainability/policy.html">Policy</a></li>
      <li><a href="https://www.nlcindia.in/website/en/aboutus/contact.html">Contact</a></li>
      <li><a href="https://www.nlcindia.in/website/en/aboutus/boardofdirectors.html">Investor</a></li>
    </ul>
  </div>

  <script>
    // Toggle Overview
    document.getElementById('overviewBtn').addEventListener('click', function(e) {
      e.preventDefault();
      var dropdown = document.getElementById('overviewDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Toggle About Us Submenu
    document.getElementById('aboutUsBtn').addEventListener('click', function(e) {
      e.preventDefault();
      var dropdown = document.getElementById('aboutUsDropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close if clicked outside
    document.addEventListener('click', function(e) {
      var overviewBtn = document.getElementById('overviewBtn');
      var overviewDropdown = document.getElementById('overviewDropdown');
      if (!overviewBtn.contains(e.target) && !overviewDropdown.contains(e.target)) {
        overviewDropdown.style.display = 'none';
        document.getElementById('aboutUsDropdown').style.display = 'none';
      }
    });
  </script>
</body>
</html>
