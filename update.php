<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Student</title>
<style>
body { 
  margin: 0; 
  font-family: Arial, sans-serif; 
  background: #f8fdfb; 
}

header { 
  background: linear-gradient(90deg, #f1c40f, #d4ac0d); 
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
  background: #d4ac0d; 
}

.menu button { 
  background: #d4ac0d; 
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
  background: #f1c40f; 
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
  color: #f1c40f; 
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
  background: #f1c40f; 
  color: white; 
  border: none; 
  border-radius: 6px; 
  font-size: 16px; 
  cursor: pointer; 
  margin-top: 10px; 
}

button.submit-btn:hover { 
  background: #d4ac0d; 
}

.back { 
  display: inline-block; 
  margin-top: 15px; 
  padding: 10px 20px; 
  background: #d4ac0d; 
  color: white; 
  text-decoration: none; 
  border-radius: 6px; 
  font-weight: bold; 
  transition: 0.3s; 
}

.back:hover { 
  background: #b7950b; 
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
<h1></h1>
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch </h4>Update Student Records
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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $table = "";
    $exam = "";
    $marks_range = 0;
    $id = 0;
    $name = "";
    $roll = "";
    $dbms = 0;
    $toc = 0;
    $cns = 0;
    $spos = 0;
    $hci = 0;

    if(isset($_POST['update_unit'])){
        $table = "unit_results"; $exam = "Unit Exam"; $marks_range = 30;
        $id = $_POST['id_unit']; $name = $_POST['name_unit']; $roll = $_POST['roll_unit'];
        $dbms = $_POST['dbms_unit']; $toc = $_POST['toc_unit']; $cns = $_POST['cns_unit'];
        $spos = $_POST['spos_unit']; $hci = $_POST['hci_unit'];
    } elseif(isset($_POST['update_prelim'])){
        $table = "prelim_results"; $exam = "Prelim Exam"; $marks_range = 70;
        $id = $_POST['id_prelim']; $name = $_POST['name_prelim']; $roll = $_POST['roll_prelim'];
        $dbms = $_POST['dbms_prelim']; $toc = $_POST['toc_prelim']; $cns = $_POST['cns_prelim'];
        $spos = $_POST['spos_prelim']; $hci = $_POST['hci_prelim'];
    } elseif(isset($_POST['update_regular'])){
        $table = "regular_results"; $exam = "Regular Exam"; $marks_range = 100;
        $id = $_POST['id_regular']; $name = $_POST['name_regular']; $roll = $_POST['roll_regular'];
        $dbms = $_POST['dbms_regular']; $toc = $_POST['toc_regular']; $cns = $_POST['cns_regular'];
        $spos = $_POST['spos_regular']; $hci = $_POST['hci_regular'];
    }

    // Validation
    $valid = true;
    if ($dbms > $marks_range || $toc > $marks_range || $cns > $marks_range || $spos > $marks_range || $hci > $marks_range || $dbms < 0 || $toc < 0 || $cns < 0 || $spos < 0 || $hci < 0) {
        $message = "Error: Marks for $exam must be between 0 and $marks_range.";
        $class = "error";
        $valid = false;
    }

    if ($valid) {
        $conn = new mysqli("localhost","root","","pccoer");
        if($conn->connect_error){
            $message = "Connection failed: ".$conn->connect_error;
            $class="error";
        } else {
            $stmt = $conn->prepare("UPDATE $table SET name=?, roll_no=?, dbms=?, toc=?, cns=?, spos=?, hci=? WHERE id=?");
            $stmt->bind_param("ssiiiiii", $name, $roll, $dbms, $toc, $cns, $spos, $hci, $id);

            if($stmt->execute()){
                if($stmt->affected_rows > 0){
                    $message = "$exam record updated successfully!";
                    $class = "success";
                } else {
                    $message = "No record found with this ID or no changes made.";
                    $class = "error";
                }
            } else {
                $message = "Error: ".$stmt->error;
                $class = "error";
            }
            $stmt->close();
            $conn->close();
        }
    }
}

if($message!=""){ echo "<div class='message $class'>$message</div>"; }
?>

<div class="card unit" style="display:block;">
<h3>Unit Exam</h3>
<form action="" method="post">
<input type="number" name="id_unit" placeholder="Student ID" required>
<input type="text" name="name_unit" placeholder="Name" required>
<input type="text" name="roll_unit" placeholder="Roll No" required>
<input type="number" name="dbms_unit" placeholder="DBMS (0-30)" required>
<input type="number" name="toc_unit" placeholder="TOC (0-30)" required>
<input type="number" name="cns_unit" placeholder="CNS (0-30)" required>
<input type="number" name="spos_unit" placeholder="SPOS (0-30)" required>
<input type="number" name="hci_unit" placeholder="HCI (0-30)" required>
<button type="submit" name="update_unit" class="submit-btn">Update Unit Exam</button>
</form>
</div>

<div class="card prelim">
<h3>Prelim Exam</h3>
<form action="" method="post">
<input type="number" name="id_prelim" placeholder="Student ID" required>
<input type="text" name="name_prelim" placeholder="Name" required>
<input type="text" name="roll_prelim" placeholder="Roll No" required>
<input type="number" name="dbms_prelim" placeholder="DBMS (0-70)" required>
<input type="number" name="toc_prelim" placeholder="TOC (0-70)" required>
<input type="number" name="cns_prelim" placeholder="CNS (0-70)" required>
<input type="number" name="spos_prelim" placeholder="SPOS (0-70)" required>
<input type="number" name="hci_prelim" placeholder="HCI (0-70)" required>
<button type="submit" name="update_prelim" class="submit-btn">Update Prelim Exam</button>
</form>
</div>

<div class="card regular">
<h3>Regular Exam</h3>
<form action="" method="post">
<input type="number" name="id_regular" placeholder="Student ID" required>
<input type="text" name="name_regular" placeholder="Name" required>
<input type="text" name="roll_regular" placeholder="Roll No" required>
<input type="number" name="dbms_regular" placeholder="DBMS (0-100)" required>
<input type="number" name="toc_regular" placeholder="TOC (0-100)" required>
<input type="number" name="cns_regular" placeholder="CNS (0-100)" required>
<input type="number" name="spos_regular" placeholder="SPOS (0-100)" required>
<input type="number" name="hci_regular" placeholder="HCI (0-100)" required>
<button type="submit" name="update_regular" class="submit-btn">Update Regular Exam</button>
</form>
</div>

<a href="admin_panel.php" class="back">⬅ Back to Dashboard</a>
</div>

<script>
function showCard(cardType, button){
  document.querySelectorAll('.card').forEach(c => c.style.display='none');
  document.querySelector('.card.'+cardType).style.display='block';
  document.querySelectorAll('.menu button').forEach(b=>b.classList.remove('active'));
  button.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const activeCard = urlParams.get('card') || 'unit';
    showCard(activeCard, document.querySelector(`.menu button[onclick*="'${activeCard}'"]`));
});
</script>

</body>
</html>
