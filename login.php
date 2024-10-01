<?php
session_start(); // 開啟 session
include("connMySQL.php");

$sql = "SELECT * FROM user";
$result = mysqli_query($link, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedIn = false; // 設定登入狀態的初始值

    while ($row = mysqli_fetch_assoc($result)) {
        if ($email === $row['email'] && password_verify($password, $row['password'])) {
            // 設定登入狀態
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $row['name'];
            $loggedIn = true; // 登入成功

            break; // 登入成功後跳出迴圈
        }
    }

    if ($loggedIn) {
        // 顯示登入成功警告訊息並導向至 index.html
        echo "<script>
            alert('登入成功！');
            window.location.href = 'index.php';
            </script>";
    } else {
        echo "<script>
            alert('帳號或密碼錯誤，請重新輸入！');
            window.location.href = 'login.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/entry.css">
    <script>
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
    <title>登入</title>
</head>
<body>
<h1>登入</h1>
<form method="POST" action="">
    <label for="email">帳號:</label>
    <input type="text" id="email" name="email" required><br>

    <label for="password">密碼:</label>
    <input type="password" id="password" name="password" required><br>
    <label for="showPassword">
        <input type="checkbox" id="showPassword" onchange="togglePasswordVisibility()"> 密碼可見
    </label>

    <input type="submit" value="登入">
</form>
<a href="signup.php">註冊</a>
</body>
</html>
