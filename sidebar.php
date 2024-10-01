<div class="sidebar" id="sidebar">
<link rel="stylesheet" type="text/css" href="css/sidebar.css">
<button id="toggleButton" onclick="toggleSidebar()">隱藏</button>  
<h2>學生成績處理系統</h2>
  <?php
  include("connMySQL.php");
  //session_start();
  echo $_SESSION['name']." 您好！";
  ?>
  <br>
  <button onclick="location.href='logout.php'" class="sidebar-button">登出</button> 
  
  <input type="button" value="登錄學生成績" name="entry" style="background-image:url('images/edit.png');padding-left:3%;" onclick="location.href='entry.php'" class="sidebar-button">
  <input type="button" value="顯示成績紀錄" name="entry" style="background-image:url('images/screen.png');padding-left:3%;" onclick="location.href='showdata1.php'" class="sidebar-button">
  <input type="button" value="顯示統計結果" name="entry" style="background-image:url('images/stats.png');padding-left:3%;" onclick="location.href='showdata2.php'" class="sidebar-button">
  <input type="button" value="顯示統計圖表" name="entry" style="background-image:url('images/pie.png');padding-left:3%;" onclick="location.href='showchart.php'" class="sidebar-button">
  <input type="button" value="排名查詢系統" name="entry" style="background-image:url('images/search.png');padding-left:3%;" onclick="location.href='getStudent_ajax.php'" class="sidebar-button">
  
</div>

<!--<button id="toggleButton" onclick="toggleSidebar()">隐藏/显示 Sidebar</button>-->

<script>
  function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("show"); // 切換CSS類別，以顯示/隱藏SIDEBAR
  }
 
</script>