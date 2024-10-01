<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>學生成績處理系統</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/sidebar.css">
</head>
<?php
session_start();

// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}
?>
<body class="index">
  <?php
  include("connMySQL.php");
  include("sidebar.php");
  ?>
  


    <center>
    <h2>學生成績處理系統</h2>
    <br>
    程式設計：110029024 蘇璿
    <br>
    <button onclick="toggleSidebar()">顯示/隱藏</button> <!-- 新增的按鈕，用於切換顯示/隱藏SIDEBAR -->
  </center>

  <script>
    /*function toggleSidebar() {
      var sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("show"); // 切換CSS類別，以顯示/隱藏SIDEBAR
    }*/
  </script>
</body>
</html>