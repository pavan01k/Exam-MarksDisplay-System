<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delete Student</title>
<style>
body { 
  margin: 0; 
  font-family: Arial, sans-serif; 
  background: #f8fdfb; 
}

header { 
  background: linear-gradient(10deg, #e74c3c, #c0392b); 
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
  background: #c0392b; 
}

.menu button { 
  background: #c0392b; 
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
  background: #e74c3c; 
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
  color: #e74c3c; 
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
  background: #e74c3c; 
  color: white; 
  border: none; 
  border-radius: 6px; 
  font-size: 16px; 
  cursor: pointer; 
  margin-top: 10px; 
}

button.submit-btn:hover { 
  background: #c0392b; 
}

.back { 
  display: inline-block; 
  margin-top: 15px; 
  padding: 10px 20px; 
  background: #c0392b; 
  color: white; 
  text-decoration: none; 
  border-radius: 6px; 
  font-weight: bold; 
  transition: 0.3s; 
}

.back:hover { 
  background: #922b21; 
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
<h5>Dy Patil Intstiute Of Engineering Management And Resarch </h5>
Delete Student Records
</header>

<div class="menu">
  <button class="active" onclick="showCard('unit')">Unit Exam</button>
  <button onclick="showCard('prelim')">Prelim Exam</button>
  <button onclick="showCard('regular')">Regular Exam</button>
</div>

<div class="container">
<?php
$message = ""; 
$class = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $conn = new mysqli("localhost","root","","pccoer");
    if($conn->connect_error){
        $message = "Connection failed: ".$conn->connect_error; 
        $class = "error";
    } else {
        if(isset($_POST['delete_unit'])){ $table="unit_results"; $exam="Unit Exam"; $id=$_POST['id_unit']; }
        elseif(isset($_POST['delete_prelim'])){ $table="prelim_results"; $exam="Prelim Exam"; $id=$_POST['id_prelim']; }
        elseif(isset($_POST['delete_regular'])){ $table="regular_results"; $exam="Regular Exam"; $id=$_POST['id_regular']; }

        $stmt = $conn->prepare("DELETE FROM $table WHERE id=?");
        $stmt->bind_param("i",$id);
        if($stmt->execute()){
            if($stmt->affected_rows>0){ 
                $message="$exam record deleted successfully!"; 
                $class="success"; 
            } else { 
                $message="No record found with this ID."; 
                $class="error"; 
            }
        } else { 
            $message="Error: ".$stmt->error; 
            $class="error"; 
        }

        $stmt->close(); 
        $conn->close();
    }
}

if($message!=""){ 
    echo "<div class='message $class'>$message</div>"; 
}
?>

<!-- Unit Exam Delete -->
<div class="card unit" style="display:block;">
<h3>Unit Exam</h3>
<form action="" method="post">
<input type="number" name="id_unit" placeholder="Enter Student ID" required>
<button type="submit" name="delete_unit" class="submit-btn">Delete Unit Exam Record</button>
</form>
</div>

<!-- Prelim Exam Delete -->
<div class="card prelim">
<h3>Prelim Exam</h3>
<form action="" method="post">
<input type="number" name="id_prelim" placeholder="Enter Student ID" required>
<button type="submit" name="delete_prelim" class="submit-btn">Delete Prelim Exam Record</button>
</form>
</div>

<!-- Regular Exam Delete -->
<div class="card regular">
<h3>Regular Exam</h3>
<form action="" method="post">
<input type="number" name="id_regular" placeholder="Enter Student ID" required>
<button type="submit" name="delete_regular" class="submit-btn">Delete Regular Exam Record</button>
</form>
</div>

<a href="admin_panel.php" class="back">⬅ Back to Dashboard</a>
</div>

<script>
function showCard(cardType){
  document.querySelectorAll('.card').forEach(c => c.style.display='none');
  document.querySelector('.card.'+cardType).style.display='block';
  document.querySelectorAll('.menu button').forEach(b => b.classList.remove('active'));
  event.currentTarget.classList.add('active');
}
</script>

</body>
</html>
