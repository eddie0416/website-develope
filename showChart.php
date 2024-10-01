<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>學生測驗成績一覽表：繪製統計圖</title>
    <link rel="stylesheet" type="text/css" href="css/showdata.css">
</head>
<body>
<button onclick="toggleSidebar()">顯示/隱藏</button> <!-- 新增的按鈕，用於切換顯示/隱藏SIDEBAR -->   
    <!-- 網站主功能按鈕(sidebar)還沒加 -->
    <br>

    <?php
    session_start();
    include("sidebar.php");
    // 檢查使用者登入狀態
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php'); // 重新導向至登入頁面
        exit;
    }
    
    include("chart_function.php");
    header("Content-Type: text/html; charset=utf-8");

    $sql1 = "SELECT deptTitle AS 系別名稱, COUNT(*) AS 人數
          FROM score, department
          WHERE score.deptNo = department.deptNo
          GROUP BY department.deptNo;";

    $sql2 = "SELECT d.deptTitle,
    SUM(CASE WHEN ((s.score1 + s.score2 + s.score3) / 3) >= 60 THEN 1 ELSE 0 END) AS pass_count
    FROM score s
    INNER JOIN department d ON s.deptNo = d.deptNo
    GROUP BY d.deptTitle";

    $sql3 = "SELECT c.club_name,
    SUM(CASE WHEN s.gender = 'M' THEN 1 ELSE 0 END) / SUM(CASE WHEN s.gender = 'F' THEN 1 ELSE 0 END) AS male_female_ratio
FROM student_club sc
INNER JOIN clubs c ON sc.club_code = c.club_code
INNER JOIN score s ON sc.stNo = s.stNo
GROUP BY c.club_name";

    $sql4 = "SELECT YEAR(birthday) AS birth_year, COUNT(*) AS count
    FROM score
    GROUP BY YEAR(birthday)";
    

    chart($sql1, $dataPoints1);
    chart($sql2, $dataPoints2);
    chart($sql3, $dataPoints3);
    chart($sql4, $dataPoints4);
    ?>

    <h1>學生測驗成績一覽表：繪製統計圖</h1>

    <div class="navbar">
        <a class="active" href="#chartContainer1">圖一</a>
        <a href="#chartContainer2">圖二</a>
        <a href="#chartContainer3">圖三</a>
        <a href="#chartContainer4">圖四</a>
    </div>

    <div id="chartContainer1" style="height: 450px; width: 45%; " align="left"></div>
    <div id="chartContainer2" style="height: 450px; width: 45%; display: none;" align="left"></div>
    <div id="chartContainer3" style="height: 450px; width: 45%; display: none;" align="left"></div>
    <div id="chartContainer4" style="height: 450px; width: 45%; display: none;" align="left"></div>


    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script type="text/javascript">
        window.onload = function () {  //圓餅圖
            var chart1 = new CanvasJS.Chart("chartContainer1", {
                title: {
                    text: "各系別人數統計圖",
                    fontSize: 30,
                    fontWeight: "bold",
                    fontColor: "#7c2f27"
                },
                legend: {
                    maxWidth: 375,
                    itemWidth: 100
                },
                data: [{
                    type: "pie",  //pie是圓餅圖、column是直條圖
                    showInLegend: true,
                    legendText: "{label}",
                    toolTipContent: "{y} - #percent %",
                    yValueFormatString: "#,##0",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();

            var chart2 = new CanvasJS.Chart("chartContainer2", {
                title: {
                    text: "各系別及格人數統計圖",
                    fontSize: 30,
                    fontWeight: "bold",
                    fontColor: "#7c2f27"
                },
                legend: {
                    maxWidth: 375,
                    itemWidth: 100
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    toolTipContent: "{y} - #percent %",
                    yValueFormatString: "#,##0",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();

            var chart3 = new CanvasJS.Chart("chartContainer3", {
                title: {
                    text: "各社團男女比",
                    fontSize: 30,
                    fontWeight: "bold",
                    fontColor: "#7c2f27"
                },
                legend: {
                    maxWidth: 375,
                    itemWidth: 100
                },
                data: [{
                    type: "column",
                    showInLegend: true,
                    legendText: "{label}",
                    toolTipContent: "{y} - #percent %",
                    yValueFormatString: "#,##0",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart3.render();

            var chart4 = new CanvasJS.Chart("chartContainer4", {
                title: {
                    text: "各年份出生人數",
                    fontSize: 30,
                    fontWeight: "bold",
                    fontColor: "#7c2f27"
                },
                legend: {
                    maxWidth: 375,
                    itemWidth: 100
                },
                data: [{
                    type: "line",
                    showInLegend: true,
                    legendText: "{label}",
                    toolTipContent: "{y} - #percent %",
                    yValueFormatString: "#,##0",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart4.render();
        }
    </script>

    <script>
        // JavaScript代碼
        // 監聽導覽列的點擊事件，根據點擊的連結顯示相應的表格
        document.querySelectorAll('.navbar a').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var target = link.getAttribute('href');
                document.querySelectorAll('div[id^="chartContainer"]').forEach(function (chart) {
                    chart.style.display = 'none';
                });
                document.querySelector(target).style.display = 'block';

                // 移除所有選項的 active 類別
                document.querySelectorAll('.navbar a').forEach(function (navItem) {
                    navItem.classList.remove('active');
                });
                // 添加 active 類別到點擊的選項
                link.classList.add('active');
            });
        });
    </script>
</body>
</html>
