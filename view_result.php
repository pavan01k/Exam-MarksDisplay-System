<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Students</title>
<style>
body { 
    margin:0; 
    font-family: Arial, sans-serif; 
    background:#f8fdfb; 
}
header { 
    background: linear-gradient(90deg, #0066cc, #00ccff); 
    color:white; 
    text-align:center; 
    padding:15px; 
    font-size:24px; 
    font-weight:bold; 
    box-shadow:0 2px 6px rgba(0,0,0,0.2);
}
.menu { 
    display:flex; 
    justify-content:center; 
    background:#00aaff; 
}
.menu button { 
    background:#00aaff; 
    color:white; 
    border:none; 
    padding:12px 20px; 
    cursor:pointer; 
    font-size:16px; 
    transition:0.3s; 
    margin:0 5px; 
    border-radius:6px 6px 0 0; 
}
.menu button:hover, 
.menu button.active { 
    background:#0066cc; 
}
.container { 
    display:flex; 
    flex-direction:column; 
    align-items:center; 
    justify-content:center; 
    padding:20px; 
    min-height:60vh; 
}
h2 { 
    margin-bottom:20px; 
    color:#0066cc; 
}
.card { 
    background:white; 
    padding:25px; 
    border-radius:8px; 
    box-shadow:0 2px 10px rgba(0,0,0,0.1); 
    width:95%; 
    max-width:1200px; 
    display:none; 
    margin-bottom:20px; 
}
table { 
    width:100%; 
    border-collapse: collapse; 
}
th, td { 
    padding:10px; 
    border-bottom:1px solid #ddd; 
    text-align:center; 
}
th { 
    background:#0066cc; 
    color:white; 
}
.unit th { background:#3498db; }
.prelim th { background:#e67e22; }
.regular th { background:#9b59b6; }

/* Subject column highlight */
.subject-th {
    background:#4682b4 !important;
    border-left:2px solid #fff;
    border-right:2px solid #fff;
}
.subject-td {
    background:#f0f8ff;
    font-weight:bold;
}
.subject-th:last-child,
.subject-td:last-child {
    border-right:none;
}
tr:hover .subject-td {
    background:#e6f3ff;
}

/* Striped rows */
tbody tr:nth-child(even) {
    background:#fafafa;
}

.back { 
    display:inline-block; 
    margin-top:15px; 
    padding:10px 20px; 
    background:#00ccff; 
    color:white; 
    text-decoration:none; 
    border-radius:6px; 
    font-weight:bold; 
    transition:0.3s; 
}
.back:hover { background:#0066cc; }

/* Exam-specific colors */
.unit .subject-th { background:#3498db !important; }
.prelim .subject-th { background:#e67e22 !important; }
.regular .subject-th { background:#9b59b6 !important; }
</style>
</head>
<body>

<header>
<h1></h1>
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch </h4>    Student Result Records
</header>

<div class="menu">
  <button class="active" onclick="showCard('unit')">Unit Exam</button>
  <button onclick="showCard('prelim')">Prelim Exam</button>
  <button onclick="showCard('regular')">Regular Exam</button>
</div>

<div class="container">
<?php
$conn = new mysqli("localhost", "root", "", "dypiemr");
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

function display_exam_card($conn, $table, $title, $class){
    $result = $conn->query("SELECT * FROM $table");
    
    // Total marks per exam
    $total_marks = ($table == "unit_results") ? 150 : (($table == "prelim_results") ? 350 : 500);
    $subject_count = 5;
    $subjects = ['dbms', 'cn', 'dc', 'mc', 'eft'];

    echo "<div class='card $class'><h2>$title</h2><table><thead>
              <tr><th>ID</th><th>Name</th><th>Roll No</th>";
    foreach($subjects as $i => $sub){
        $last = ($i == count($subjects)-1) ? " last" : "";
        echo "<th class='subject-th$last'>".strtoupper($sub)."</th>";
    }
    echo "<th>Overall Score</th><th>Average</th><th>Percentage</th></tr></thead><tbody>";
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $aggregate = array_sum(array_intersect_key($row, array_flip($subjects)));
            $average = round($aggregate / $subject_count, 2);
            $percentage = round(($aggregate / $total_marks) * 100, 2);
            $overall_score = $aggregate . '/' . $total_marks;

            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['roll_no']}</td>";
            foreach($subjects as $i => $sub){
                $last = ($i == count($subjects)-1) ? " last" : "";
                echo "<td class='subject-td$last'>{$row[$sub]}</td>";
            }
            echo "<td>{$overall_score}</td>
                  <td>{$average}</td>
                  <td>{$percentage}%</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='11'>No records found</td></tr>";
    }
    
    echo "</tbody></table></div>";
}

display_exam_card($conn, "unit_results", "📘 Unit Exam Records", "unit");
display_exam_card($conn, "prelim_results", "📙 Prelim Exam Records", "prelim");
display_exam_card($conn, "regular_results", "📗 Regular Exam Records", "regular");

$conn->close();
?>
<a href="admin_panel.php" class="back">⬅ Back to Dashboard</a>
</div>

<script>
function showCard(cardType){
  document.querySelectorAll('.card').forEach(c=>c.style.display='none');
  document.querySelector('.card.'+cardType).style.display='block';
  document.querySelectorAll('.menu button').forEach(b=>b.classList.remove('active'));
  event.currentTarget.classList.add('active');
}
document.addEventListener('DOMContentLoaded',()=>{ showCard('unit'); });
</script>

</body>
</html>
