<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Insert Student Records</title>
<style>
body { 
  margin: 0; 
  font-family: Arial, sans-serif; 
  background: #f8fdfb; 
}

header { 
  background: linear-gradient(90deg, #2ecc71, #27ae60); 
  color: white; 
  text-align: center; 
  padding: 15px; 
  font-size: 24px; 
  font-weight: bold; 
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.menu { 
  display: flex; 
  justify-content: center; 
  background: #27ae60; 
}

.menu button { 
  background: #27ae60; 
  color: white; 
  border: none; 
  padding: 12px 20px; 
  cursor: pointer; 
  font-size: 16px; 
  transition: 0.3s; 
  margin: 0 5px; 
  border-radius: 6px 6px 0 0; 
}

.menu button:hover, 
.menu button.active { 
  background: #2ecc71; 
}

.container { 
  display: flex; 
  flex-direction: column; 
  align-items: center; 
  justify-content: center; 
  padding: 20px; 
}

h2 { 
  margin-bottom: 20px; 
  color: #2ecc71; 
}

.card { 
  background: white; 
  padding: 25px; 
  border-radius: 8px; 
  box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
  width: 400px; 
  margin-bottom: 20px; 
  display: none; 
}

input { 
  width: 100%; 
  padding: 10px; 
  margin: 10px 0; 
  border: 1px solid #ccc; 
  border-radius: 6px; 
  font-size: 14px; 
}

button.submit-btn { 
  width: 100%; 
  padding: 12px; 
  background: #2ecc71; 
  color: white; 
  border: none; 
  border-radius: 6px; 
  font-size: 16px; 
  cursor: pointer; 
  margin-top: 10px; 
}

button.submit-btn:hover { 
  background: #27ae60; 
}

.back { 
  display: inline-block; 
  margin-top: 15px; 
  padding: 10px 20px; 
  background: #27ae60; 
  color: white; 
  text-decoration: none; 
  border-radius: 6px; 
  font-weight: bold; 
  transition: 0.3s; 
}

.back:hover { 
  background: #229954; 
}

.message { 
  margin: 15px 0; 
  padding: 12px; 
  border-radius: 6px; 
  text-align: center; 
  font-weight: bold; 
}

.success { 
  background: #d4edda; 
  color: #155724; 
}

.error { 
  background: #f8d7da; 
  color: #721c24; 
}

.card.unit { 
  border-top: 5px solid #3498db; 
}

.card.prelim { 
  border-top: 5px solid #e67e22; 
}

.card.regular { 
  border-top: 5px solid #9b59b6; 
}

.card h3 { 
  margin-bottom: 15px; 
  text-align: center; 
  color: #333; 
}
</style>
</head>
<body>

<header>
  <h4>Dy Patil Institute Of Engineering Management And Research</h4>
  Insert Student Records
</header>

<div class="menu">
  <button class="active" onclick="showCard('unit', this)">Unit Exam</button>
  <button onclick="showCard('prelim', this)">Prelim Exam</button>
  <button onclick="showCard('regular', this)">Regular Exam</button>
</div>

<div class="container">

<?php
$message = "";
$class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $table = "";
    $exam_name = "";
    $marks_range = 0;
    $name = "";
    $roll = "";
    $dbms = 0;
    $cn = 0;
    $dc = 0;
    $mc = 0;
    $eft = 0;

    if(isset($_POST['submit_unit'])){
        $table = "unit_results";
        $exam_name = "Unit Exam";
        $marks_range = 30;
        $name = $_POST['name_unit'];
        $roll = $_POST['roll_unit'];
        $dbms = $_POST['dbms_unit'];
        $cn = $_POST['cn_unit'];
        $dc = $_POST['dc_unit'];
        $mc = $_POST['mc_unit'];
        $eft = $_POST['eft_unit'];
    } elseif(isset($_POST['submit_prelim'])){
        $table = "prelim_results";
        $exam_name = "Prelim Exam";
        $marks_range = 70;
        $name = $_POST['name_prelim'];
        $roll = $_POST['roll_prelim'];
        $dbms = $_POST['dbms_prelim'];
        $cn = $_POST['cn_prelim'];
        $dc = $_POST['dc_prelim'];
        $mc = $_POST['mc_prelim'];
        $eft = $_POST['eft_prelim'];
    } elseif(isset($_POST['submit_regular'])){
        $table = "regular_results";
        $exam_name = "Regular Exam";
        $marks_range = 100;
        $name = $_POST['name_regular'];
        $roll = $_POST['roll_regular'];
        $dbms = $_POST['dbms_regular'];
        $cn = $_POST['cn_regular'];
        $dc = $_POST['dc_regular'];
        $mc = $_POST['mc_regular'];
        $eft = $_POST['eft_regular'];
    }

    // Validation
    $valid = true;
    if ($dbms > $marks_range || $cn > $marks_range || $dc > $marks_range || $mc > $marks_range || $eft > $marks_range || $dbms < 0 || $cn < 0 || $dc < 0 || $mc < 0 || $eft < 0) {
        $message = "Error: Marks for $exam_name must be between 0 and $marks_range.";
        $class = "error";
        $valid = false;
    }

    if ($valid) {
        $conn = new mysqli("localhost", "root", "", "dypiemr");
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
            $class = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO $table (name, roll_no, dbms, cn, dc, mc, eft) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiiiii", $name, $roll, $dbms, $cn, $dc, $mc, $eft);

            if ($stmt->execute()) {
                $message = "$exam_name record inserted successfully!";
                $class = "success";
            } else {
                $message = "Error: " . $stmt->error;
                $class = "error";
            }
            $stmt->close();
            $conn->close();
        }
    }
}

if($message != ""){
    echo "<div class='message $class'>$message</div>";
}
?>

<div class="card unit" style="display:block;">
  <h3>Unit Exam</h3>
  <form action="" method="post">
    <input type="text" name="name_unit" placeholder="Name" required>
    <input type="text" name="roll_unit" placeholder="Roll No" required>
    <input type="number" name="dbms_unit" placeholder="DBMS (0-30)" required>
    <input type="number" name="cn_unit" placeholder="CN (0-30)" required>
    <input type="number" name="dc_unit" placeholder="DC (0-30)" required>
    <input type="number" name="mc_unit" placeholder="MC (0-30)" required>
    <input type="number" name="eft_unit" placeholder="EFT (0-30)" required>
    <button type="submit" name="submit_unit" class="submit-btn">Insert Unit Exam</button>
  </form>
</div>

<div class="card prelim">
  <h3>Prelim Exam</h3>
  <form action="" method="post">
    <input type="text" name="name_prelim" placeholder="Name" required>
    <input type="text" name="roll_prelim" placeholder="Roll No" required>
    <input type="number" name="dbms_prelim" placeholder="DBMS (0-70)" required>
    <input type="number" name="cn_prelim" placeholder="CN (0-70)" required>
    <input type="number" name="dc_prelim" placeholder="DC (0-70)" required>
    <input type="number" name="mc_prelim" placeholder="MC (0-70)" required>
    <input type="number" name="eft_prelim" placeholder="EFT (0-70)" required>
    <button type="submit" name="submit_prelim" class="submit-btn">Insert Prelim Exam</button>
  </form>
</div>

<div class="card regular">
  <h3>Regular Exam</h3>
  <form action="" method="post">
    <input type="text" name="name_regular" placeholder="Name" required>
    <input type="text" name="roll_regular" placeholder="Roll No" required>
    <input type="number" name="dbms_regular" placeholder="DBMS (0-100)" required>
    <input type="number" name="cn_regular" placeholder="CN (0-100)" required>
    <input type="number" name="dc_regular" placeholder="DC (0-100)" required>
    <input type="number" name="mc_regular" placeholder="MC (0-100)" required>
    <input type="number" name="eft_regular" placeholder="EFT (0-100)" required>
    <button type="submit" name="submit_regular" class="submit-btn">Insert Regular Exam</button>
  </form>
</div>

<a href="admin_panel.php" class="back">⬅ Back to Dashboard</a>
</div>

<script>
function showCard(cardType, button) {
    document.querySelectorAll('.card').forEach(c => c.style.display='none');
    document.querySelector('.card.' + cardType).style.display='block';
    document.querySelectorAll('.menu button').forEach(b => b.classList.remove('active'));
    button.classList.add('active');
}

// Initial display on page load
document.addEventListener('DOMContentLoaded', () => {
    const errorMessage = document.querySelector('.message.error');
    if (errorMessage) {
        const urlParams = new URLSearchParams(window.location.search);
        const activeCard = urlParams.get('card') || 'unit';
        showCard(activeCard, document.querySelector(`.menu button[onclick*="'${activeCard}'"]`));
    } else {
        showCard('unit', document.querySelector('.menu button.active'));
    }
});
</script>

</body>
</html>
