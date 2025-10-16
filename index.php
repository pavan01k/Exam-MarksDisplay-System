<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Result System - Home</title>
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
      background: #2c3e50;
      color: white;
      padding: 15px;
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
      padding: 40px;
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
    .admin { border-top: 4px solid #3498db; }
    .student { border-top: 4px solid #2ecc71; }
  </style>
</head>
<body>
  <header>
   <h1></h1>
  <h4>Dy Patil Intstiute Of Engineering Management And Resarch</h4>
  

    Online Result System
  </header>
  
  <div class="dashboard">
    <div class="card admin" onclick="openPage('login.php')">
      <h3>🛠 Admin Panel</h3>
      <p>Manage students and exams</p>
    </div>
    <div class="card student" onclick="openPage('stud_login.php')">
      <h3>🎓 Student Panel</h3>
      <p>View your results</p>
    </div>
  </div>

  <script>
    function openPage(page) {
      window.location.href = page;
    }
  </script>
</body>
</html>
