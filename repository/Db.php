<?php

class Db
{
    private string $host = 'localhost';
    private string $user = 'root';
    private string $password = '';
    private string $dbName = 'readme';

    protected mysqli $con;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $con = mysqli_connect($this->host, $this->user, $this->password, $this->dbName);
        mysqli_set_charset($con, "utf8");
        $this->con = $con;
    }
}
