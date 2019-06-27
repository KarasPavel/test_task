<?php

class DatabaseConnection
{
    private $con;

    public static function get_instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance->con;
    }

    protected static $instance;

    private function __construct()
    {
        $this->con = mysqli_connect("127.0.0.1", "vlastit", "7773410rR", "review");
        $this->con->set_charset("utf8");
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }
}

$GLOBALS['dbConnection'] = DatabaseConnection::get_instance();

