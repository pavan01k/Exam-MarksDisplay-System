<?php 
$message = ''; 
$result_data = null; 
$total_marks = 0; 
$exam_title = '';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    $conn = new mysqli("localhost", "root", "", "dypiemr");     
    if ($conn->connect_error) {         
        $message = "Connection failed: " . $conn->connect_error;     
    } else {         
        $exam_type = $_POST['exam_type'];         
        $roll_no = $_POST['roll_no'];         
        $student_name = $_POST['name'];          

        // Determine the table name and total marks based on the selected exam type         
        $table_name = '';         
        if ($exam_type == 'unit') {             
            $table_name = 'unit_results';             
            $total_marks = 150;             
            $exam_title = 'Unit Exam';         
        } elseif ($exam_type == 'prelim') {             
            $table_name = 'prelim_results';             
            $total_marks = 350;             
            $exam_title = 'Prelim Exam';         
        } elseif ($exam_type == 'regular') {             
            $table_name = 'regular_results';             
            $total_marks = 500;             
            $exam_title = 'Regular Exam';         
        }          

        if ($table_name) {             
            $stmt = $conn->prepare("SELECT * FROM `$table_name` WHERE `roll_no` = ? AND `name` = ?");             
            $stmt->bind_param("ss", $roll_no, $student_name);             
            $stmt->execute();             
            $result = $stmt->get_result();             

            if ($result->num_rows > 0) {                 
                $result_data = $result->fetch_assoc();             
            } else {                 
                $message = "No record found for the provided details. Please check your roll number and name.";             
            }             
            $stmt->close();         
        } else {             
            $message = "Invalid exam type selected.";         
        }         
        $conn->close();     
    } 
} 
?>  

<!DOCTYPE html> 
<html lang="en"> 
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Student Result</title>     
    <style>         
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            min-height: 100vh; 
        }         
        header { 
            background: #007bff; 
            color: white; 
            padding: 15px; 
            text-align: center; 
            font-size: 24px; 
            font-weight: bold; 
            width: 100%; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.2); 
        }         
        .container { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            max-width: 600px; 
            width: 90%; 
            margin-top: 30px; 
        }         
        h2 { 
            text-align: center; 
            color: #34495e; 
            margin-bottom: 20px; 
        }         
        .form-group { 
            margin-bottom: 15px; 
        }         
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #555; 
        }         
        input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 6px; 
            font-size: 16px; 
            box-sizing: border-box; 
        }         
        .form-btn { 
            width: 100%; 
            padding: 12px; 
            background: #28a745; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 18px; 
            cursor: pointer; 
            transition: 0.3s; 
        }         
        .form-btn:hover { 
            background: #218838; 
        }         
        .message { 
            margin-top: 20px; 
            padding: 15px; 
            border-radius: 6px; 
            font-weight: bold; 
            text-align: center; 
        }         
        .success { 
            background: #d4edda; 
            color: #155724; 
        }         
        .error { 
            background: #f8d7da; 
            color: #721c24; 
        }         
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }         
        th, td { 
            padding: 12px; 
            border: 1px solid #ddd; 
            text-align: left; 
        }         
        th { 
            background: #007bff; 
            color: white; 
        }         
        .result-table th, .result-table td { 
            text-align: center; 
        }          

        /* New styles for cards */         
        .card-selection { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-bottom: 20px; 
            font-size: 20px; 
            font-weight: bold; 
        }         
        .card {              
            background: #f8f9fa;              
            border: 2px solid #ddd;              
            border-radius: 8px;              
            padding: 20px;              
            text-align: center;              
            cursor: pointer;              
            transition: 0.3s;              
            flex: 1;         
        }         
        .card:hover { 
            border-color: #007bff; 
            background: #e9f5ff; 
        }         
        .card.selected { 
            border-color: #007bff; 
            background: #346ff7 ; 
            box-shadow: 0 0 10px rgba(0,123,255,0.2); 
        }     
    </style> 
</head> 
<body>  

<header> 
    <h1></h1>   
    <h4>Dy Patil Intstiute Of Engineering Management And Resarch</h4>    
    Student Result Portal 
</header>  

<div class="container">     
    <h2>View Your Result</h2>     
    <?php if ($message): ?>         
        <div class="message <?php echo strpos($message, 'No record') !== false || strpos($message, 'Invalid') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>     
    <?php endif; ?>      

    <form action="" method="post" id="resultForm">         
        <div class="card-selection">             
            <div class="card" data-exam-type="unit" onclick="selectCard(this)">Unit Exam</div>             
            <div class="card" data-exam-type="prelim" onclick="selectCard(this)">Prelim Exam</div>             
            <div class="card" data-exam-type="regular" onclick="selectCard(this)">Regular Exam</div>         
        </div>         
        <input type="hidden" name="exam_type" id="exam_type_input" required>                  

        <div class="form-group">             
            <label for="roll_no">Roll No:</label>             
            <input type="text" id="roll_no" name="roll_no" required>         
        </div>         
        <div class="form-group">             
            <label for="name">Full Name:</label>             
            <input type="text" id="name" name="name" required>         
        </div>         
        <button type="submit" class="form-btn">View Result</button>     
    </form>          

    <?php if ($result_data): ?>         
        <div class="message success">
            Result found for <?php echo htmlspecialchars($result_data['name']); ?> (Roll No: <?php echo htmlspecialchars($result_data['roll_no']); ?>)
        </div>         
        <table class="result-table">             
            <thead>                 
                <tr>                     
                    <th colspan="2"><?php echo $exam_title; ?> Marks</th>                 
                </tr>             
            </thead>             
            <tbody>                 
                <tr><td>DBMS</td><td><?php echo htmlspecialchars($result_data['dbms']); ?></td></tr>                 
                <tr><td>CN</td><td><?php echo htmlspecialchars($result_data['cn']); ?></td></tr>                 
                <tr><td>DC</td><td><?php echo htmlspecialchars($result_data['dc']); ?></td></tr>                 
                <tr><td>MC</td><td><?php echo htmlspecialchars($result_data['mc']); ?></td></tr>                 
                <tr><td>EFT</td><td><?php echo htmlspecialchars($result_data['eft']); ?></td></tr>             
            </tbody>             
            <tfoot>                 
                <?php                     
                    $aggregate = $result_data['dbms'] + $result_data['cn'] + $result_data['dc'] + $result_data['mc'] + $result_data['eft'];                     
                    $average = round($aggregate / 5, 2);                     
                    $percentage = round(($aggregate / $total_marks) * 100, 2);                 
                ?>                 
                <tr><th>Overall Score</th><td><?php echo $aggregate . ' / ' . $total_marks; ?></td></tr>                 
                <tr><th>Average</th><td><?php echo $average; ?></td></tr>                 
                <tr><th>Percentage</th><td><?php echo $percentage; ?>%</td></tr>             
            </tfoot>         
        </table>     
    <?php endif; ?> 
</div>  

<script>     
    function selectCard(card) {         
        // Remove 'selected' class from all cards         
        document.querySelectorAll('.card').forEach(c => c.classList.remove('selected'));         
        // Add 'selected' class to the clicked card         
        card.classList.add('selected');         
        // Set the value of the hidden input field         
        document.getElementById('exam_type_input').value = card.getAttribute('data-exam-type');     
    } 
</script>  

</body> 
</html>
