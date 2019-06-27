<?php
require_once 'TableAbstract.php';
require_once 'Image.php';
require_once 'Subject.php';

class Review extends TableAbstract
{
    protected $folder = 'reviews';
    protected $imageType = 1;
    protected $availablePerPage = [7, 14, 21, 70];

    public function __construct()
    {
        $this->table = 'reviews';
        $this->fields = ['id', 'username', 'review', 'subject_id', 'image_id', 'like_counter', 'created_at', 'deleted_at'];
        $this->pk = 'id';
    }


    /**
     * @param string $username
     * @param string $review
     * @param int $subject_id
     * @param array $imageFile
     * @param int $like_counter
     */
    public function createReview(string $username, string $review, int $subject_id, array $imageFile, int $like_counter)
    {
        $subjectCheck = new Subject();
        $subjectCheck = $subjectCheck->getSubjects();

        foreach ($subjectCheck as $value) {
            $subjectCheck[$value[0]] = $value[0];
        }
        if (!in_array($subject_id, $subjectCheck)) {
            die("Неверно введены данные");
        }

        if ((int)$like_counter < 0) {
            $like_counter = 0;
        }
        if ($imageFile['name']) {
            $image = new Image();
            $imageId = $image->uploadImage((string)$this->folder,
                (array)$imageFile, (int)$this->imageType);
            $imageId = $imageId[0][0];
        } else {
            $imageId = null;
        }

        $query = "INSERT INTO " . $this->table .
            " SET username = ?,
            review = ?,
            subject_id = ?,
            image_id = ?,
            like_counter = ?";

        $createReview = $GLOBALS['dbConnection']->prepare($query);
        $result = $createReview->bind_param("ssiii", $username, $review, $subject_id, $imageId, $like_counter);
        if ($result === true) {
            $result = $createReview->execute();
        } else {
            $result = $GLOBALS['dbConnection']->rollback();
            die("Неверно введены данные");
        }
    }

    public function analytics()
    {
        $subjects = new Subject();
        $subjects = $subjects->getSubjects();

        foreach ($subjects as $value) {
            $query = "select subject,sum(case when subjects.id = " . $value[0] . " then 1 else 0 end) counter " .
                "from " . $this->table . " inner join subjects on subjects.id = reviews.subject_id group by subjects.id";

            $result = mysqli_query($GLOBALS['dbConnection'], $query);
            $result = mysqli_fetch_all($result);
            $data[$value[0]] = $result;
        }

        if ($data[1][0][0]) {
            $data[1][0][0] = "Клиенты нас любят";
        } else {
            $data[1][0][0] = "Клиенты нас любят";
            $data[1][0][1] = 0;
        }
        if ($data[2][1][0]) {
            $data[2][1][0] = "Пора меняться";
        } else {
            $data[2][1][0] = "Пора меняться";
            $data[2][1][1] = 0;
        }
        if ($data[3][2][0]) {
            $data[3][2][0] = "Надо сжечь это место";
        } else {
            $data[3][2][0] = "Надо сжечь это место";
            $data[3][2][1] = 0;
        }

        return $data;

    }

    public function getReviewlist(int $page, int $perPage, $sort)
    {
        if (!isset($page)) {
            $page = 1;
        }
        if (!isset($perPage) || !in_array($perPage, $this->availablePerPage)) {
            $perPage = 7;
        }

        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

        $query = "select * from " . $this->table;
        $total = mysqli_query($GLOBALS['dbConnection'], $query);
        $total = mysqli_num_rows($total);
        $totalPages = ceil($total / $perPage);

        if ($page > $totalPages) {
            $start = 0;
        }

        $sql = "SELECT username,review,reviews.created_at,subject,like_counter,images.image FROM " .
            $this->table .
            " inner join subjects on reviews.subject_id = subjects.id" .
            " left join images on reviews.image_id = images.id";
        if ($sort === 1) {
            $sql = $sql . " order by reviews.id" .
                " LIMIT " . $start . ',' . $perPage;
        } else {
            $sql = $sql . " order by reviews.id desc" .
                " LIMIT " . $start . ',' . $perPage;
        }


        $result = mysqli_query($GLOBALS['dbConnection'], $sql);
        $result = mysqli_fetch_all($result);
        foreach ($result as $value) {
            if ($value[5]) {
                $value[5] = substr($value[5], 45, 100);
            } else {
                $value[5] = "0";
            }
            echo $value[2] . "<br/><b>Пользователь:</b> " . $value[0] .
                '<br><b>Тематика отзыва: </b>' . $value[3] .
                '<br> <b>Коментарий:</b> ' . $value[1] .
                '<br> <b> Количество лайков:</b> ' . $value[4];
            if ($value[5]) {
                echo '<br/><img src="' . $value[5] . '" 
                  width="255" height="255"' .
                    "<br/><br />";
            } else {
                echo "<br/><br/>";
            }
        }


        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a id="pages" href="?page=' . $i . '&perPage=' . $perPage . '&sort=' . $sort . '" > ' . $i . ' </a>';
        }


    }
}
