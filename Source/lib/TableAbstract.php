<?php

require_once 'DatabaseConnection.php';

abstract class TableAbstract

{
    protected $table;
    protected $fields;
    protected $pk;

    public function __construct()
    {
    }

}