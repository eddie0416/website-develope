<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <title>計算學生成績&存檔</title>
</head>

<body class="process">
  <input id="button" type="button" value="回系統主畫面" name="entry"
    style="background-image:url('images/home.png');padding-left:3%;" onclick="location.href='index.html'">
  <input id="button" type="button" value="登錄學生成績" name="entry"
    style="background-image:url('images/edit.png');padding-left:3%;" onclick="location.href='entry.php'">
  <input id="button" type="button" value="顯示成績紀錄" name="entry"
    style="background-image:url('images/screen.png');padding-left:3%;" onclick="location.href='showdata1.php'">
  <input id="button" type="button" value="顯示統計結果" name="entry"
    style="background-image:url('images/stats.png');padding-left:3%;" onclick="location.href='showdata2.php'">
  <?php
  session_start();

  // 檢查使用者登入狀態
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
      header('Location: login.php'); // 重新導向至登入頁面
      exit;
  }
  
  include("connMySQL.php");
  mysqli_query($link, "SET CHARACTER SET UTF8");
  date_default_timezone_set('Asia/Taipei');
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receive_method = "* 資料接收方式：POST";
    $stno = $_POST['stNo'];
    $name = $_POST['stName'];
    $gender = $_POST['gender'];
    $grade = $_POST['grade'];
    $department = $_POST['department'];
    $selected_clubs = $_POST['club']; // 接收 club[] 陣列
    $birth = $_POST['birthday'];
    $year = substr($_POST['birthday'], 0, 4);
    $month = intval(substr($_POST['birthday'], 5, 2));
    $date = intval(substr($_POST['birthday'], 8, 2));
    $score_ch = $_POST['score_ch'];
    $score_en = $_POST['score_en'];
    $score_pg = $_POST['score_pg'];
  }

  function getAge($birth)
  { //計算年齡函式
    $age = date_diff(date_create($birth), date_create('today'))->y;
    return $age;
  }
  $age = getAge($birth);

  function level($score)
  { //計算等第
    if ($score == 100) {
      echo "<font font-weight:bold color='#3e8e41'>(優良)</font>";
    } elseif ($score >= 90) {
      echo "<font font-weight:bold color='#3e8e41'>(甲等)</font>";
    } elseif ($score >= 80) {
      echo "<font font-weight:bold color='#3e8e41'>(乙等)</font>";
    } elseif ($score >= 70) {
      echo "<font font-weight:bold color='#3e8e41'>(丙等)</font>";
    } elseif ($score >= 60) {
      echo "<font font-weight:bold color='#3e8e41'>(丁等)</font>";
    } else {
      echo "<font font-weight:bold color='red'>(不及格)</font>";
    }
  }


  $sum = $score_ch + $score_en + $score_pg;
  $avg = round($sum / 3, 1);

  echo "<h2>計算學生成績&存檔</h2>";
  echo "<font color='#48579f'><b>$receive_method</b></font>";
  echo "<br>";
  echo "* 學號：$stno";
  echo "<br>";
  echo "* 姓名：$name";
  echo "<br>";
  echo "* 生理性別：$gender";
  echo "<br>";
  echo "* 年級：$grade 年級";
  echo "<br>";
  echo "* 年齡：$age 歲";
  echo "<br>";
  echo "* 系別：$department";
  $sql = "SELECT deptTitle FROM `department` WHERE deptNo = '$department'";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result);
  echo "(" . $row['deptTitle'] . ")";
  echo "<br>";
  echo "* 社團：";
  $total_clubs = count($selected_clubs);//selected_clubs為一由club_code組成的陣列
  $counter = 0;
  foreach ($selected_clubs as $club) { //逐個index提取selected_clubs陣列中的元素到club變數中
    $counter++;
    $sql = "SELECT club_name FROM `clubs` WHERE club_code = '$club'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    echo $row['club_name'];
    if ($counter != $total_clubs) { //如果是最後一個index則不加"/"
      echo "/";
    }
  }
  echo "<br>";
  echo "* 文學創作成績：$score_ch 分";
  echo "<br>";
  echo "* 英文閱讀成績：$score_en 分";
  echo "<br>";
  echo "* 程式設計成績：$score_pg 分";
  echo "<br>";
  echo "<br>";
  echo "* $name ";
  if ($gender == "M") {
    echo "先生";
  } else {
    echo "小姐";
  }
  echo " 好！您的總分：$sum 平均：$avg";
  echo "<font>" . level($avg) . "</font>";
  echo "<br>";
  echo "<br>";
  foreach ($selected_clubs as $club) { //先將該學號所有的club加到student_club資料表中
    $sql = "INSERT INTO `student_club` VALUES ('$stno','$club')";
    $result = mysqli_query($link, $sql);
  }
  $sql = "insert into score
        values('$stno', '$name', '$gender',$grade, 
        '$birth', '$department', $score_ch, $score_en, $score_pg)";
  if (mysqli_query($link, $sql)) {
    echo ("資料已新增至資料庫！");
  } else {
    echo ("資料新增失敗！");
  }

  mysqli_close($link);
  ?>
</body>

</html>