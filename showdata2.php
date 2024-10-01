<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/showdata.css">
<title>顯示各系成績統計結果</title>
</head>
<?php
session_start(); // 開啟 session
include("sidebar.php");
// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}

include("connMySQL.php");
?>
<body>
<button onclick="toggleSidebar()">顯示/隱藏</button> <!-- 新增的按鈕，用於切換顯示/隱藏SIDEBAR -->   
    <!-- 導覽列 -->
<div class="navbar">
  <a class="active" href="#table1">表一</a>
  <a href="#table2">表二</a>
  <a href="#table3">表三</a>
  <a href="#table4">表四</a>
</div>

<!-- 表一 -->
<div id="table1">
  <!-- 表一的內容 -->
  <?php
    $sql = "SELECT AVG(score1) AS avg1,AVG(score2) AS avg2,AVG(score3) AS avg3
    FROM score";
    $result = mysqli_query($link,$sql);
    $row = mysqli_fetch_assoc($result);

    echo "<table>";
    echo "<tr><th>科目</th><th>總平均</th></tr>";
    echo "<tr><td>文學創作</td><td>".$row['avg1']."</td></tr>";
    echo "<tr><td>英文閱讀</td><td>".$row['avg2']."</td></tr>";
    echo "<tr><td>程式設計</td><td>".$row['avg3']."</td></tr>";
    echo "</table>";
  ?>
</div>

<!-- 表二 -->
<div id="table2">
  <!-- 表二的內容 -->
  <?php
    $sql = "SELECT d.deptTitle,
    SUM(CASE WHEN ((s.score1 + s.score2 + s.score3) / 3) >= 60 THEN 1 ELSE 0 END) AS pass_count,
    SUM(CASE WHEN ((s.score1 + s.score2 + s.score3) / 3) < 60 THEN 1 ELSE 0 END) AS fail_count
    FROM score s
    INNER JOIN department d ON s.deptNo = d.deptNo
    GROUP BY d.deptTitle";
    $result = mysqli_query($link,$sql);
    $pass_count = 0;
    $fail_count = 0;

    echo "<table>";
    echo "<tr><th>系別名稱</th><th>及格人數</th><th>不及格人數</th></tr>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$row['deptTitle']."</td>";
        echo "<td>".$row['pass_count']."</td>";
        echo "<td>".$row['fail_count']."</td>";
        echo "</tr>";
        $pass_count+=$row['pass_count'];
        $fail_count+=$row['fail_count'];
    }
    echo "<tr><td>總計</td><td>".$pass_count."</td><td>".$fail_count."</td></tr>";
    echo "</table>";
  ?>
</div>

<!-- 表三 -->
<div id="table3">
  <!-- 表三的內容 -->
  <?php
    $sql = "SELECT s.gender,
    SUM(CASE WHEN ((s.score1 + s.score2 + s.score3) / 3) >= 60 THEN 1 ELSE 0 END) AS pass_count,
    SUM(CASE WHEN ((s.score1 + s.score2 + s.score3) / 3) < 60 THEN 1 ELSE 0 END) AS fail_count
    FROM score s
    GROUP BY s.gender";
    $result = mysqli_query($link,$sql);
    $pass_count = 0;
    $fail_count = 0;

    echo "<table>";
    echo "<tr><th>性別</th><th>及格人數</th><th>不及格人數</th></tr>";
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        if($row['gender'] == "M"){
            echo "<td>男生</td>"; 
        }else{
            echo "<td>女生</td>"; 
        }
        echo "<td>".$row['pass_count']."</td>";
        echo "<td>".$row['fail_count']."</td>";
        echo "</tr>";
        $pass_count+=$row['pass_count'];
        $fail_count+=$row['fail_count'];
    }
    echo "<tr><td>總計</td><td>".$pass_count."</td><td>".$fail_count."</td></tr>";
    echo "</table>";
  ?>
  </div>
<!-- 表四 -->
<div id="table4">
  <!-- 表四的內容 -->
  <?php
$sql = "SELECT gender, AVG((score1+score2+score3)/3) as avg
    FROM score
    GROUP BY gender";
    $result = mysqli_query($link,$sql);
    echo "<table>";
    echo "<tr><th>性別</th><th>總平均</th></tr>";
    while($row = mysqli_fetch_assoc($result)){
      if($row['gender'] == "M"){
        echo "<tr><td>男生總平均</td><td>";
    }else{
        echo "<tr><td>女生總平均</td><td>";
    }
    echo round($row['avg'],2)."</td></tr>";
  }
  echo "</table>";
    ?>
</div>

<script>
// JavaScript代碼
// 監聽導覽列的點擊事件，根據點擊的連結顯示相應的表格
document.querySelectorAll('.navbar a').forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    var target = link.getAttribute('href');
    document.querySelectorAll('div[id^="table"]').forEach(function(table) {
      table.style.display = 'none';
    });
    document.querySelector(target).style.display = 'block';
    
    // 移除所有選項的 active 類別
    document.querySelectorAll('.navbar a').forEach(function(navItem) {
      navItem.classList.remove('active');
    });
    // 添加 active 類別到點擊的選項
    link.classList.add('active');
  });
});

</script>
</body>
</html>