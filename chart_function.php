<?php
function chart($sql, &$dataPoints) {  // 修改函式簽名以接收數據點陣列的引數
  include("connMySQL.php");
  mysqli_query($link, "SET CHARACTER SET UTF8");  
  $result = mysqli_query($link, $sql);

  $sendData1 = "";  //系別名稱
  $sendData2 = "";  //人數 
  for ($i=1; $i<=mysqli_num_rows($result); $i++) { //該函數是計算result有幾筆資料
    $data = mysqli_fetch_row($result);
    if ($i<mysqli_num_rows($result)) {
      $sendData1 = $sendData1.$data[0].",";
      $sendData2 = $sendData2.$data[1].",";
    } else {
      $sendData1 = $sendData1.$data[0];//如果是最後一筆，就不加，
      $sendData2 = $sendData2.$data[1];
    }  
  }  
  mysqli_free_result($result);
  $label_of_department = explode(",", $sendData1);
  $num_of_registration = explode(",", $sendData2); 

  //$result = mysqli_query($link, $sql);  // 不需要再次執行查詢，因為已經在 index.php 中執行過了

  $dataPoints = array();  // 將數據點陣列清空，以便重新賦值

  for ($i = 0; $i < count($label_of_department); $i++) {  // 使用 count() 取得陣列的長度
    $dataPoints[] = array("label" => $label_of_department[$i], "y" => $num_of_registration[$i]);
  }
}
?>
