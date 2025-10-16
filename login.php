<?php
session_start();

$message = '';
$class = '';

// Database credentials
$host = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "dypiemr";

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

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $captcha_input = $_POST['captcha_input'] ?? '';

    // CAPTCHA check
    if (empty($captcha_input) || strtoupper($captcha_input) !== $_SESSION['captcha']) {
        $message = "Invalid CAPTCHA code. Please try again.";
        $class = "error";
        $_SESSION['captcha'] = generateCaptcha();
    } else {
        $conn = new mysqli($host, $dbUser, $dbPass, $dbName);
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
            $class = "error";
        } else {
            // Validate username + password
            $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    unset($_SESSION['captcha']);
                    header("Location: admin_panel.php");
                    exit;
                } else {
                    $message = "Invalid username or password.";
                    $class = "error";
                    $_SESSION['captcha'] = generateCaptcha();
                }
            } else {
                $message = "Invalid username or password.";
                $class = "error";
                $_SESSION['captcha'] = generateCaptcha();
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
<title>Admin Login</title>
<style>
body { margin:0; font-family: Arial, sans-serif; background: #f4f6f9; }
header { background: linear-gradient(90deg, #3498db, #2980b9); color:white; text-align:center; padding:15px; font-size:24px; font-weight:bold; box-shadow:0 2px 6px rgba(0,0,0,0.2);}
.container { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:20px; min-height:80vh; }
.form-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); text-align: center; width: 350px; }
h1 { color: #34495e; margin-bottom: 10px; }
h2 { color: #34495e; margin-bottom: 20px; font-weight: normal; }
input { width: calc(100% - 22px); padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 6px; font-size: 18px; cursor: pointer; margin-top: 10px; transition: 0.3s; }
button:hover { background: #2980b9; }
.message { margin: 15px 0; padding: 12px; border-radius: 6px; font-weight: bold; }
.success { background: #d4edda; color: #155724; }
.error { background: #f8d7da; color: #721c24; }
.captcha-container { display: flex; align-items: center; justify-content: center; margin: 10px 0; }
.captcha-text { font-size: 24px; font-weight: bold; letter-spacing: 5px; padding: 5px 10px; background: #e9ecef; border-radius: 6px; font-family: monospace; transform: rotate(-2deg); }
.refresh-btn { background: #f1c40f; color: #fff; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 14px; margin-left: 10px; }
.refresh-btn:hover { background: #d4ac0d; }
input[name="captcha_input"] { text-transform: uppercase; }
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
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch </h4>    <h2>Admin Login</h2>
</header>
<div class="container">
    <div class="form-container">
        <?php if ($message): ?>
            <div class="message <?php echo $class; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <div class="captcha-container">
                <div id="captchaText" class="captcha-text"><?php echo $_SESSION['captcha']; ?></div>
                <button type="button" class="refresh-btn" onclick="refreshCaptcha();">Refresh</button>
            </div>
            <input type="text" name="captcha_input" placeholder="Enter CAPTCHA" required autocomplete="off">
            <button type="submit">Login</button>
        </form>
        <p style="margin-top: 15px;">Don't have an account? <a href="sign.php">Sign up now</a></p>
    </div>
</div>
</body>
</html>
