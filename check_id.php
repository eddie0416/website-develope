<?php
include("connMySQL.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];

  $sql = "SELECT COUNT(*) AS count FROM user WHERE email = '$email'";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];

  if ($count > 0) {
    $error_message = "此帳號已存在！";
    echo $error_message;
  }
}

mysqli_close($link);
?>
