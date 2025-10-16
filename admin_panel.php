<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Online Result System</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 10vh;
    }
    header {
      background: #2c3e50;
      color: white;
      padding: 10px;
      text-align: center;
      font-size: 24px;
      width: 100%;
    }
    .dashboard {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 30px;
      max-width: 900px;
      width: 100%;
    }
    .card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: 0.3s;
      width: 220px;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .card h3 { margin: 10px 0; font-size: 20px; color: #34495e; }
    .card p { color: #7f8c8d; font-size: 14px; }
    .view { border-top: 4px solid #3498db; }
    .insert { border-top: 4px solid #2ecc71; }
    .update { border-top: 4px solid #f1c40f; }
    .delete {
      border-top: 4px solid #e74c3c;
      flex: 1 1 100%;     /* occupy full row */
      max-width: 300px;   /* keep it smaller */
      margin: 0 auto;     /* center align */
    }
  </style>
</head>
<body>
  <header>
<h1></h1>
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch </h4>
	Admin Dashboard - Online Result System</header>
  
  <div class="dashboard">
    <div class="card view" onclick="openPage('view_result.php')">
      <h3>📊 View Students</h3>
      <p>See all students and their marks</p>
    </div>
    <div class="card insert" onclick="openPage('insert.php')">
      <h3>➕ Insert Data</h3>
      <p>Add new student record</p>
    </div>
    <div class="card update" onclick="openPage('update.php')">
      <h3>✏️ Update Data</h3>
      <p>Modify existing records</p>
    </div>
    <div class="card delete" onclick="openPage('delete.php')">
      <h3>🗑️ Delete Data</h3>
      <p>Remove student record</p>
    </div>
  </div>

  <script>
    function openPage(page) {
      window.location.href = page;
    }
  </script>
</body>
</html>
