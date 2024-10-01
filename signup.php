<?php
include("connMySQL.php");
session_start();

// 檢查是否已經登入
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // 已經登入，重新導向至 index.html
    header("Location: index.php");
    exit;
}else{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"];
        $name = $_POST["name"];
        $password = $_POST["password"];
        $birthday = $_POST["birthday"];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user VALUES('$email','$name','$hashedPassword','$birthday')";
      
        if(mysqli_query($link,$sql)){
            echo "<script>
                alert('註冊成功！');
                window.location.href = 'login.php';
                </script>";  
        }else{
            echo "註冊失敗，請聯絡網頁管理員。";
        }
        
        $mysqli->close();
      }
}
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>註冊</title>
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
    $(function () {  //檢查email是否重複
      $("#email").blur(function () {
        var email = $(this).val(); // 抓取email

        $.ajax({
          url: "check_id.php",
          type: "POST",
          data: { email: email }, // 將email傳到 check_id.php檢查
          success: function (response) {
            if (response.trim() !== '') {
              // email驗證不通過，顯示錯誤消息
              $('#errorContainer').text(response);
              $("#email").addClass('invalid').removeClass('valid');
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
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var showPasswordCheckbox = document.getElementById("showPassword");
  
  if (showPasswordCheckbox.checked) {
    passwordInput.type = "text";
  } else {
    passwordInput.type = "password";
  }
}
  </script>
</head>

<body>
  <h2>註冊</h2>
  <form id=form action="" method="POST"><!--如果action設為空字串，表單提交後將重新加載當前頁面-->
  <!--<form id=form action="process.php" method="POST" onsubmit="return validateForm()">-->
    1.電子郵件：<input type="email" class="myInput" name="email" size="16" maxlength="50" required>
    <span id="errorContainer"></span>
    <br>
    2.暱稱：<input type="text" class="myInput" name="name" size="16" maxlength="10" required
      pattern="[a-zA-Z\u4e00-\u9fa5]+">
    <br>
    <label for="password">3.密碼：</label>
    <input type="password" id="password" class="myInput" name="password" required pattern="[a-zA-Z\d]{1,10}">
    <label for="showPassword">
        <input type="checkbox" id="showPassword" onchange="togglePasswordVisibility()"> 密碼可見
    </label>
    <br>
    4.生日：<input type="date" name="birthday" value="2002-01-01"> <!--傳送到PHP的形式是"2023-02-03"-->
    <br>
    <input type="submit" value="註冊" style="background-image:url('images/submit.png');padding-left:3%;">
    <input type="reset" value="重新填寫資料" style="background-image:url('images/reset.png');padding-left:3%;">
  </form>
</body>
</html>