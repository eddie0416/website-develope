<?php
header("Content-Type: text/html; charset=utf-8");
session_start();

// 檢查使用者登入狀態
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // 重新導向至登入頁面
    exit;
}

$id = $_GET["id"];
include("connMySQL.php");

if (isset($_POST["action"]) && ($_POST["action"] == "delete")) {
    $sql_delete_student = "DELETE FROM score WHERE stNo='$id'";
    echo $sql_delete_student;
    mysqli_query($link, $sql_delete_student);
    //重新導向回到主畫面
    header("Location: showData1.php");
}
$sql_student = "SELECT score.stNo, stName, gender, grade, birthday, deptTitle, GROUP_CONCAT(clubs.club_name SEPARATOR '/') AS club,
    score1, score2, score3
    FROM score
    JOIN department ON score.deptno = department.deptno
    JOIN student_club ON score.stNo = student_club.stNo
    JOIN clubs ON student_club.club_code = clubs.club_code
    GROUP BY score.stNo";

$result_student = mysqli_query($link, $sql_student);
$row_result = mysqli_fetch_assoc($result_student);
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/update.css">
    <title>學生成績處理系統：刪除資料</title>
</head>

<body>
    <h1>學生成績處理系統：刪除資料</h1>
    <p><a href="showData1.php">顯示成績紀錄</a></p>
    <form action="" method="post" name="formDel" id="formDel">
        <table>
            <tr>
                <th>欄位名稱</th>
                <th>資料內容</th>
            </tr>
            <tr>
                <td>學號</td>
                <td>
                    <?php echo $id; ?>
                </td>
            </tr>
            <tr>
                <td>姓名</td>
                <td>
                    <?php echo $row_result["stName"]; ?>
                </td>
            </tr>
            <tr>
                <td>生理性別</td>
                <td>
                    <?php
                    if ($row_result["gender"] == "M") {
                        echo "男";
                    } else {
                        echo "女";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>生日</td>
                <td>
                    <?php echo $row_result["birthday"]; ?>
                </td>
            </tr>
            <tr>
                <td>年級</td>
                <td>
                    <?php echo $row_result["grade"]; ?>
                </td>
            </tr>
            <tr>
                <td>系別</td>
                <td>
                    <?php echo $row_result["deptTitle"]; ?>
                </td>
            </tr>
            <tr>
                <td>社團</td>
                <td>
                    <?php echo $row_result["club"]; ?>
                </td>
            </tr>
            <tr>
                <td>文學創作</td>
                <td>
                    <?php echo $row_result["score1"]; ?>
                </td>
            </tr>
            <tr>
                <td>英文閱讀</td>
                <td>
                    <?php echo $row_result["score2"]; ?>
                </td>
            </tr>
            <tr>
                <td>程式設計</td>
                <td>
                    <?php echo $row_result["score3"]; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="id" type="hidden" value='<?php echo $row_result["id"]; ?>'>
                    <input name="action" type="hidden" value="delete">
                    <input type="submit" name="button" id="button" value="確定刪除這筆資料嗎？">
                    <input id="button" type="button" value="取消" name="entry" onclick="location.href='showdata1.php'">
                </td>
            </tr>
        </table>
    </form>
</body>

</html>