<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>AJAX：查詢學生成績</title>
<link rel="stylesheet" type="text/css" href="css/entry.css">
<script>
function showHint(str) {
  if (str.length == 0) {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getStudent.php?stNo=" + str, true);
    xmlhttp.send();
  }
}
</script>
</head>

<body>
<?php
  session_start();
include("sidebar.php");
// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}
?>
<button onclick="toggleSidebar()">顯示/隱藏</button>
<br>
<h3>請輸入要查詢的學生資料：</h3>
<form action="">
  <label for="stNo">學號：</label>
  <input type="text" id="stNo" name="stNo" onkeyup="showHint(this.value)"><!--onkeyup就是當框框有輸入資料時就交給ajax去做處理-->
</form>
<h2>查詢結果：</h2>
<span id="txtHint"></span>
</body>
</html>