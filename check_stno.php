<?php
include("connMySQL.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stNo = $_POST['stNo'];

  $sql = "SELECT COUNT(*) AS count FROM score WHERE stNo = '$stNo'";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];

  if ($count > 0) {
    $error_message = "此學號已存在！";
    echo $error_message;
  }
}

mysqli_close($link);
?>
