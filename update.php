<?php
session_start();

// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}

header("Content-Type: text/html; charset=utf-8");
$id = $_GET["id"];
include("connMySQL.php");
include("selectFunction.php");

if (isset($_POST["action"]) && ($_POST["action"] == "update")) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['stName'];
        $gender = $_POST['gender'];
        $grade = $_POST['grade'];
        $department = $_POST['department'];
        $birth = $_POST['birthday'];
        $score1 = $_POST['score1'];
        $score2 = $_POST['score2'];
        $score3 = $_POST['score3'];
        
        // 檢查是否存在名為 "club" 的索引且為陣列形式
        if (isset($_POST['club']) && is_array($_POST['club'])) {
            $selected_clubs = $_POST['club'];
            
            // 先將該學號在student_club資料表中的所有紀錄刪除
            $sql = "DELETE FROM `student_club` WHERE stNo='$id'";
            $result = mysqli_query($link, $sql);
            
            // 新增新的紀錄
            foreach ($selected_clubs as $club) {
                $sql = "INSERT INTO `student_club` VALUES ('$id','$club')";
                $result = mysqli_query($link, $sql);
            }
        }        
        // 更新學生成績資料
        $sql = "UPDATE `score` SET `stName`='$name',`gender`='$gender',`grade`='$grade',
        `birthday`='$birth',`deptNo`='$department',`score1`=$score1,`score2`=$score2,`score3`=$score3 
        WHERE stNo='$id'";
        
        if($result = mysqli_query($link, $sql)){
            header("Location: showData1.php");
        }
    }
}

$sql_student = "SELECT score.stNo, stName, gender, grade, birthday, deptTitle, GROUP_CONCAT(clubs.club_name SEPARATOR '/') AS club,
    score1, score2, score3
    FROM score
    JOIN department ON score.deptno = department.deptno
    JOIN student_club ON score.stNo = student_club.stNo
    JOIN clubs ON student_club.club_code = clubs.club_code
    WHERE score.stNo='$id'
    GROUP BY score.stNo";

$result_student = mysqli_query($link, $sql_student);
$row_result = mysqli_fetch_assoc($result_student);
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>學生成績處理系統：修改資料</title>
    <link rel="stylesheet" type="text/css" href="css/update.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<body>
    <h1>學生成績處理系統：修改資料</h1>
    <p><a href="showData1.php">顯示成績紀錄</a></p>
    <!--<form action="" method="post" name="formDel" id="formDel">-->
    <form id="formUpdate" name="formUpdate" action="" method="POST" onsubmit="return validateForm()">
        <table>
            <tr>
                <th>欄位名稱</th>
                <th>資料內容</th>
            </tr>
            <tr>
                <td>學號</td>
                <td>
                    <?php echo $id; ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                </td>
            </tr>
            <tr>
                <td>姓名</td>
                <td>
                    <input type="text" class="myInput" name="stName" size="16" maxlength="10" required pattern="[a-zA-Z\u4e00-\u9fa5]+" value="<?php echo $row_result["stName"]; ?>">
                </td>
            </tr>
            <tr>
                <td>生理性別</td>
                <td>
                    <input type="radio" name="gender" value="M" <?php if ($row_result["gender"] == "M") echo "checked"; ?>>男
                    <input type="radio" name="gender" value="F" <?php if ($row_result["gender"] == "F") echo "checked"; ?>>女
                </td>
            </tr>
            <tr>
                <td>生日</td>
                <td>
                    <input type="date" name="birthday" value="<?php echo $row_result["birthday"]; ?>">
                </td>
            </tr>
            <tr>
                <td>年級</td>
                <td>
                    <input type="radio" name="grade" value="1" <?php if ($row_result["grade"] == "1") echo "checked"; ?>>一年級
                    <input type="radio" name="grade" value="2" <?php if ($row_result["grade"] == "2") echo "checked"; ?>>二年級
                    <input type="radio" name="grade" value="3" <?php if ($row_result["grade"] == "3") echo "checked"; ?>>三年級
                    <input type="radio" name="grade" value="4" <?php if ($row_result["grade"] == "4") echo "checked"; ?>>四年級
                    <input type="radio" name="grade" value="5" <?php if ($row_result["grade"] == "5") echo "checked"; ?>>五年級
                    <input type="radio" name="grade" value="6" <?php if ($row_result["grade"] == "6") echo "checked"; ?>>六年級
                    <input type="radio" name="grade" value="7" <?php if ($row_result["grade"] == "7") echo "checked"; ?>>七年級
                </td>
            </tr>
            <tr>
                <td>系別</td>
                <td>
                    <?php update_dept($id); ?> <!--name = department-->
                </td>
            </tr>
            <tr>
                <td>社團</td>
                <td>
                    <?php update_club($id); ?> <!--name = club-->
                </td>
            </tr>
            <tr>
                <td>文學創作</td>
                <td>
                    <input type="number" class="myInput" name="score1" size="16" maxlength="9" min="0" max="100" required value="<?php echo $row_result["score1"]; ?>">
                </td>
            </tr>
            <tr>
                <td>英文閱讀</td>
                <td>
                    <input type="number" class="myInput" name="score2" size="16" maxlength="9" min="0" max="100" required value="<?php echo $row_result["score2"]; ?>">
                </td>
            </tr>
            <tr>
                <td>程式設計</td>
                <td>
                    <input type="number" class="myInput" name="score3" size="16" maxlength="9" min="0" max="100" required value="<?php echo $row_result["score3"]; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="action" type="hidden" value="update">
                    <input type="submit" name="button" id="button" value="確定修改這筆資料嗎？">
                    <input id="button" type="button" value="取消" name="entry" onclick="location.href='showData1.php'">
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
