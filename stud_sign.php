<?php
session_start();

$message = '';
$class = '';

// Database credentials
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "pccoer";

// Function to generate CAPTCHA
function generateCaptcha() {
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captcha = '';
    for ($i = 0; $i < 6; $i++) {
        $captcha .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $captcha;
}

// Initialize CAPTCHA
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = generateCaptcha();
}

// AJAX CAPTCHA refresh
if (isset($_GET['refresh_captcha'])) {
    $_SESSION['captcha'] = generateCaptcha();
    echo $_SESSION['captcha'];
    exit;
}

// Handle registration POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $captcha_input = $_POST['captcha_input'] ?? '';

    // CAPTCHA check
    if (empty($captcha_input) || strtoupper($captcha_input) !== $_SESSION['captcha']) {
        $message = "Invalid CAPTCHA code. Please try again.";
        $class = "error";
        $_SESSION['captcha'] = generateCaptcha();
    } elseif (empty($username) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
        $class = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $class = "error";
    } else {
        $conn = new mysqli($host, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
            $class = "error";
        } else {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM students WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $message = "Username already exists. Choose another.";
                $class = "error";
                $_SESSION['captcha'] = generateCaptcha();
            } else {
                // Insert new student with hashed password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO students (username, password) VALUES (?, ?)");
                $insert->bind_param("ss", $username, $hashed_password);
                if ($insert->execute()) {
					$message = "Registration successful! You can now log in.";
					$class = "success";
					$_SESSION['captcha'] = generateCaptcha(); // refresh instead of unset
				} else {
					$message = "Error registering student. Try again.";
					$class = "error";
					$_SESSION['captcha'] = generateCaptcha();
				}

                $insert->close();
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Sign Up</title>
<style>
body { margin:0; font-family: Arial, sans-serif; background: #e6f2ff; }
header { background: linear-gradient(90deg, #4facfe, #00f2fe); color:white; text-align:center; padding:15px; font-size:24px; font-weight:bold; box-shadow:0 2px 6px rgba(0,0,0,0.2);}
.container { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:20px; min-height:80vh; }
.form-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); text-align: center; width: 350px; }
h1 { color: #34495e; margin-bottom: 10px; }
h2 { color: #34495e; margin-bottom: 20px; font-weight: normal; }

/* Input and buttons */
input { width: calc(100% - 22px); padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
button { width: 100%; padding: 12px; background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; border: none; border-radius: 6px; font-size: 18px; cursor: pointer; margin-top: 10px; transition: 0.3s; }
button:hover { background: linear-gradient(135deg, #38f9d7, #43e97b); }
.message { margin: 15px 0; padding: 12px; border-radius: 6px; font-weight: bold; }
.success { background: #d4edda; color: #155724; }
.error { background: #f8d7da; color: #721c24; }

/* CAPTCHA */
.captcha-container { display: flex; align-items: center; justify-content: center; margin: 10px 0; }
.captcha-text { font-size: 24px; font-weight: bold; letter-spacing: 5px; padding: 5px 10px; background: #e9ecef; border-radius: 6px; font-family: monospace; transform: rotate(-2deg); }
.refresh-btn { background: #6c757d; color: #fff; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 14px; margin-left: 10px; }
.refresh-btn:hover { background: #495057; }
input[name="captcha_input"] { text-transform: uppercase; }

a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
<script>
function refreshCaptcha() {
    fetch('?refresh_captcha=1')
        .then(response => response.text())
        .then(data => {
            document.getElementById('captchaText').textContent = data;
        });
}

document.addEventListener("DOMContentLoaded", function() {
    const captchaInput = document.querySelector('input[name="captcha_input"]');
    captchaInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
</head>
<body>
<header>
<h1></h1>
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch </h4>    <h2>Student Sign Up</h2>
</header>
<div class="container">
    <div class="form-container">
        <?php if ($message): ?>
            <div class="message <?php echo $class; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <div class="captcha-container">
                <div id="captchaText" class="captcha-text"><?php echo $_SESSION['captcha']; ?></div>
                <button type="button" class="refresh-btn" onclick="refreshCaptcha();">Refresh</button>
            </div>
            <input type="text" name="captcha_input" placeholder="Enter CAPTCHA" required autocomplete="off">
            <button type="submit">Sign Up</button>
        </form>
        <p style="margin-top: 15px;">Already have an account? <a href="stud_login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
