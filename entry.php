<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>登錄學生成績</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/entry.css">
  
  <script>
    $(document).ready(function () {
      var inputs = $('.myInput');
      inputs.on('input', function () {
        if (this.checkValidity()) {
          $(this).addClass('valid').removeClass('invalid');
        } else {
          $(this).removeClass('valid').addClass('invalid');
        }
      });
    });
    $(function () {
      $("#stNo").blur(function () {
        var stNo = $(this).val(); // 抓取學號

        $.ajax({
          url: "check_stno.php",
          type: "POST",
          data: { stNo: stNo }, // 將學號傳到 check_stno.php檢查
          success: function (response) {
            if (response.trim() !== '') {
              // 學號驗證不通過，顯示錯誤消息
              $('#errorContainer').text(response);
              $("#stNo").addClass('invalid').removeClass('valid');
              // 可以根據需要進行其他操作，例如禁用提交按鈕
            } else {
              $('#errorContainer').empty();
            }
          },
          error: function () {
            $("#result").text("ERROR,please check php or server situation.");
          }
        });
      });
    });
    function validateForm() {
      var checkboxes = document.getElementsByName('club[]');
      var isChecked = false;

      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
          isChecked = true;
          break;
        }
      }

      if (!isChecked) {
        alert('請至少選擇一個社團！');
        return false;
      }

      return true;
    }
  </script>
</head>
<?php
session_start();
include("sidebar.php"); //包含側邊攔

// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}
?>
<body>
<button id="toggleButton" onclick="toggleSidebar()">顯示/隱藏</button> <!-- 新增的按鈕，用於切換顯示/隱藏SIDEBAR -->
  
<div class="style1">登錄學生成績</div>
  <form id=form action="process.php" method="POST" onsubmit="return validateForm()">
    1.學號：<input type="text" id="stNo" class="myInput" name="stNo" size="16" minlength="9" maxlength="9" required
      pattern="\d+">
    <span id="errorContainer"></span>
    <br>
    2.姓名：<input type="text" class="myInput" name="stName" size="16" maxlength="10" required
      pattern="[a-zA-Z\u4e00-\u9fa5]+">
    <br>
    3.生理性別：
    <input type="radio" name="gender" value="M" checked>男
    <input type="radio" name="gender" value="F">女
    <br>
    4.年級：
    <input type="radio" name="grade" value="1" checked>一年級
    <input type="radio" name="grade" value="2">二年級
    <input type="radio" name="grade" value="3">三年級
    <input type="radio" name="grade" value="4">四年級
    <input type="radio" name="grade" value="5">五年級
    <input type="radio" name="grade" value="6">六年級
    <input type="radio" name="grade" value="7">七年級
    <br>
    5.生日：<input type="date" name="birthday" value="2002-01-01"> <!--傳送到PHP的形式是"2023-02-03"-->
    <br>
    6.系別：
    <?php //php開始，資料庫寫法
    include("connMySQL.php");
    include("selectFunction.php");
    selectItem('department', 'deptTitle', 'deptNo', 'department'); //這個函數是寫在selectFunction.php內
    mysqli_close($link);
    ?>
    <br>
    7.請勾選您有興趣參加的社團(可複選)：<br>
    <?php
    include("connMySQL.php");
    $sql = "SELECT club_Code, club_Name FROM clubs";
    $result = mysqli_query($link, $sql);

    if ($result) {
      // 迴圈生成社團選項
      while ($row = mysqli_fetch_assoc($result)) {
        $club_Code = $row['club_Code'];
        $club_Name = $row['club_Name'];
        $checked = ($club_Code === 'C01') ? 'checked="checked"' : '';
        echo '<input type="checkbox" name="club[]" id="' . $club_Code . '" value="' . $club_Code . '" ' . $checked . '> ' . $club_Name . ' '; //因此是post club_code到process.php
      }

      mysqli_free_result($result);
    } else {
      echo "資料庫查詢失敗！";
    }

    // 關閉資料庫連線
    mysqli_close($link);
    ?>
    <br>
    8.文學創作：<input type="number" class="myInput" name="score_ch" size="16" maxlength="9" min="0" max="100" required>
    <br>
    9.英文閱讀：<input type="number" class="myInput" name="score_en" size="16" maxlength="9" min="0" max="100" required>
    <br>
    10.程式設計：<input type="number" class="myInput" name="score_pg" size="16" maxlength="9" min="0" max="100" required>
    <br>
    <input type="submit" value="開始計算成績" style="background-image:url('images/submit.png');padding-left:3%;">
    <input type="reset" value="重新填寫資料" style="background-image:url('images/reset.png');padding-left:3%;">
  </form>

</body>

</html>