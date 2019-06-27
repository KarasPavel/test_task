<?php
require_once 'TableAbstract.php';
require_once 'UploadImageInterface.php';

class Image extends TableAbstract implements UploadImageInterface
{
    protected $folder = 'images';
    protected $availableFormats = ['image/png', 'image/jpg', 'image/jpeg'];

    public function __construct()
    {
        $this->table = 'images';
        $this->fields = ['id', 'image_type_id', 'image', 'created_at', 'deleted_at'];
        $this->pk = 'id';
    }

    /**
     * @param string $folder
     * @param array $image
     * @param int $imageType
     * @return array|bool|null
     */
    public function uploadImage(string $folder, array $image, int $imageType)
    {
        $tmp = $image['tmp_name'];
        $error = $image['error'];

        if ($error !== UPLOAD_ERR_OK || !is_uploaded_file($tmp)) {
            $errorMessage = 'При загрузке файла произошла ошибка';
            die($errorMessage);
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = (string)finfo_file($fileInfo, $tmp);

        if (in_array($mime, $this->availableFormats) === false) {
            $errorMessage = 'Можно загружать только изображения(jpg,png) типа.';
            die($errorMessage);
        }

        $format = explode('/', $mime);
        $format = $format[1];

        $fileName = md5_file($tmp);

        $destination = __DIR__ . '/../' . $this->folder . '/' . $folder . '/' . $fileName . '.' . $format;
        if (!move_uploaded_file($tmp, $destination)) {
            die('При записи изображения произошла ошибка.');
        }

        $query = "insert into " . $this->table .
            " set image_type_id = " . $imageType . ' , ' .
            "image = " . "'$destination'";

        $result = mysqli_query($GLOBALS['dbConnection'], $query);
        if ($result) {
            $query = "select id as imageId from " . $this->table .
                " where image = " . "'$destination'" .
                " order by imageId desc limit 1";
            $result = mysqli_query($GLOBALS['dbConnection'], $query);
            return mysqli_fetch_all($result);
        }
        return false;
    }
}