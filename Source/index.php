<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        Review list
    </title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages': ['corechart']});

        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Top');
            data.addColumn('number', 'Slices');
            data.addRows([
                <?php
                require_once('lib/Review.php');
                $analytics = new Review();
                $analytics = $analytics->analytics();
                $y = 0;
                for ($i = 1; $i <= count($analytics); $i++) {
                    echo("['" . $analytics[$i][$y][0] . "'" . ',' . $analytics[$i][$y][1] . "],");
                    $y++;
                }
                ?>
            ]);
            var options = {
                'title': 'Аналитика отзывов',
                'width': 600,
                'height': 400
            };
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<form action="review_form.php" method="POST" enctype="multipart/form-data">
    <label>ФИО</label><br/>
    <input type="text" name="username" required placeholder="ФИО"/>
    <br/>
    <br/>
    <label>Тематика отзыва</label><br/>
    <?php
    require_once "lib/Subject.php";
    $subjectList = new Subject();
    $subjectList = $subjectList->getSubjects();
    foreach ($subjectList as $value) {
        echo "
        <label>
        <input type='radio' value=$value[0] name='subject' required/>
        $value[1]
        </label>";
        echo "<br>";
    }
    ?>
    <br/>
    <label>Отзыв</label>
    <br/>
    <textarea name="review" cols="40" rows="20" required></textarea>
    <br>
    <label>количество лайков</label>
    <br/>
    <input type="number" name="likeCounter"/>
    <br/>
    <br/>
    <label>Изображение</label>
    <input type="file" name="image">
    <br/>
    <br/>
    <input type="text" size="15" name="captcha" required placeholder="Введите число с картинки">
    <br>
    <img src="captcha.php">
    <br>
    <br>
    <button type="submit" name="submit">
        Отправить
    </button>
</form>
<br>
<labe>Отзывы</labe>
<br/>
<br/>
<a>
    Количество отзывов на странице
</a>
<p><select id="perPage">
        <option value="none">Не выбрано</option>
        <option value="7">7</option>
        <option value="14">14</option>
        <option value="21">21</option>
        <option value="70">70</option>
    </select></p>
<br/>
<a>
    Сортировка по дате
</a>
<p><select id="dateSort">
        <option value="none">Не выбрано</option>
        <option value="0">Сначала новые</option>
        <option value="1">Сначала старые</option>
    </select></p>
<div id="reviews">
    <?php
    require_once "lib/Review.php";
    $reviews = new Review();
    $reviews->getReviewlist((int)$_GET['page'], (int)$_GET['perPage'], (int)$_GET['sort']);
    ?>
</div>
<div id="chart_div"></div>
</body>
<script src="js/perPage.js"></script>
</html>
