<?php
include 'TableAbstract.php';

class ImageType extends TableAbstract
{

    public function __construct()
    {
        $this->table = 'image_types';
        $this->fields = ['id', 'type', 'created_at', 'deleted_at'];
        $this->pk = 'id';
    }

}