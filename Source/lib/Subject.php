<?php
require_once 'TableAbstract.php';

class Subject extends TableAbstract
{

    public function __construct()
    {
        $this->table = 'subjects';
        $this->fields = ['id', 'subject', 'created_at', 'deleted_at'];
        $this->pk = 'id';
    }

    public function getSubjects()
    {
        $query = "select id,subject from " . $this->table;
        $result = mysqli_query($GLOBALS['dbConnection'], $query);
        return mysqli_fetch_all($result);
    }
}