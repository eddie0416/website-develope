<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/showdata.css">
    <title>學員測驗成績一覽表</title>
</head>

<body>
<button onclick="toggleSidebar()">顯示/隱藏</button> <!-- 新增的按鈕，用於切換顯示/隱藏SIDEBAR -->    
<?php
    session_start();
    include("sidebar.php"); 
    // 檢查使用者登入狀態
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php'); // 重新導向至登入頁面
        exit;
    }
    
    include("connMySQL.php");
    date_default_timezone_set('Asia/Taipei');
    $sql = "SELECT score.stNo, stName, gender, grade, deptTitle, GROUP_CONCAT(clubs.club_name SEPARATOR '/') AS club,
            score1, score2, score3, score1 + score2 + score3 AS total,
            ROUND((score1 + score2 + score3) / 3, 1) AS avg,
            FLOOR(DATEDIFF(CURRENT_DATE, birthday) / 365) AS age  
            FROM score
            JOIN department ON score.deptno = department.deptno
            JOIN student_club ON score.stNo = student_club.stNo
            JOIN clubs ON student_club.club_code = clubs.club_code
            GROUP BY score.stNo";
    $result = mysqli_query($link, $sql);
    $total_records = mysqli_num_rows($result); //計算資料庫列數
    ?>
    <h1>學員測驗成績一覽表</h1>
    <p>目前資料筆數：
        <?php echo $total_records; ?>。
    </p>
    <table>
        <!-- 表格表頭 -->
        <tr> <!--tr通常是表示橫列-->
            <th>序號</th>
            <th>學號</th>
            <th>姓名</th>
            <th>生理性別</th>
            <th>年級</th>
            <th>年齡</th>
            <th>系別</th>
            <th>社團</th>
            <th>文學創作</th>
            <th>英文閱讀</th>
            <th>程式設計</th>
            <th>測驗總分</th>
            <th>測驗平均</th>
            <th>異動資料</th>
        </tr>
        <!-- 資料內容 -->
        <?php
        $row = 1;
        while ($row_result = mysqli_fetch_assoc($result)) {
          if ($row % 2 == 0) {
            echo "<tr>";    
          } else {
            echo "<tr>";    
          }    
          echo "<td>$row</td>";
          echo "<td>".$row_result["stNo"]."</td>";
          echo "<td>".$row_result["stName"]."</td>";
          echo "<td>".$row_result["gender"]."</td>";
          echo "<td>".$row_result["grade"]."</td>";
          echo "<td>".$row_result["age"]."</td>";
          echo "<td>".$row_result["deptTitle"]."</td>";
          echo "<td>".$row_result["club"]."</td>";
          echo "<td>".$row_result["score1"]."</td>";
          echo "<td>".$row_result["score2"]."</td>";
          echo "<td>".$row_result["score3"]."</td>";
          echo "<td>".$row_result["total"]."</td>";
          echo "<td>".$row_result["avg"]."</td>";
          echo "<td><a href='update.php?id=".$row_result["stNo"]."'>修改 </a>"; //id對應到學號(stNo)
          echo "<a href='delete.php?id=".$row_result["stNo"]."'>刪除</a></td>";
          echo "</tr>";
          $row ++;
        }
        ?>
    </table>
</body>

</html>