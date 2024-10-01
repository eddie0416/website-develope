<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>查詢學員資料</title>
<link rel="stylesheet" type="text/css" href="css/showdata.css">
</head>

<body>
<?php

include("connMySQL.php");
// getStudent.php
function get_student_by_id($id) {
  include("connMySQL.php");

  // Prepare the query
  $sql = "SELECT s1.STNAME, s1.STNO, d.DEPTTITLE, (s1.score1+s1.score2+s1.score3)/3 AS avg_score,
  CONCAT(RANK() OVER (PARTITION BY s1.DEPTNO ORDER BY (s1.score1+s1.score2+s1.score3)/3 DESC), '/', dept_count.count) AS rank
FROM score s1
INNER JOIN department d ON s1.DEPTNO = d.DEPTNO
INNER JOIN (SELECT DEPTNO, COUNT(*) AS count FROM score GROUP BY DEPTNO) AS dept_count ON s1.DEPTNO = dept_count.DEPTNO
WHERE s1.STNO = '$id'";

  // Execute the query
  //mysqli_query($conn, "SET CHARACTER SET UTF8");  //不加中文會出現亂碼 
  $result = mysqli_query($link, $sql);

  // Check if the query was successful
  if (!$result) {
    die("Query failed: " . mysqli_error($link));
  }

  //Initialize array variable
  $dbdata = [];
  // Fetch the result
  $row = mysqli_fetch_assoc($result);
  $dbdata[] = $row;
  // Close the database connection
  mysqli_close($link);
  // Return array in JSON format
  return json_encode($dbdata, JSON_UNESCAPED_UNICODE);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the user ID parameter from the request body
  $stNo = $_POST['stNo'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $stNo = $_GET['stNo'];
}  

// Call the get_user_by_id function to retrieve the user data
$student = get_student_by_id($stNo);

// Return the user data as a JSON response
header('Content-Type: application/json');
// json_encode()：將陣列轉換成 json 格式的指令
// json_decode()：將 json 格式轉換回物件或陣列的指令
//echo $user;

# 將 JSON 格式資料轉換為 PHP 物件
$obj = json_decode($student, true);

$sql = "select * from score where stno='$stNo'";
$result = mysqli_query($link,$sql);
if(mysqli_num_rows($result) == 1){
    
# 檢視結果
    //將rank拆解為兩個數字後做%運算
    $numbers = explode('/', $obj[0]["rank"]);
        $numerator = $numbers[0];
        $denominator = $numbers[1];
        $percentage = ($numerator / $denominator) * 100;
echo "<table>";
echo "<tr>";
echo "<th>姓名</th><th>學號</th><th>系所</th><th>平均分數</th><th>系排名</th>";
echo "<th>名次占全系</th>";
echo "</tr>";
echo "<tr>";
echo "<td>".$obj[0]["STNAME"]."</td>";
echo "<td>".$obj[0]["STNO"]."</td>";
echo "<td>".$obj[0]["DEPTTITLE"]."</td>";
echo "<td>".round($obj[0]["avg_score"], 2)."</td>";
echo "<td>".$obj[0]["rank"]."</td>";
echo "<td>".$percentage."%</td>";  
echo "</tr>";  
echo "</table>";
}

?>
</body>
</html>
