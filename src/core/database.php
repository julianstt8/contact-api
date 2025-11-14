<?php

class Database
{
    private $host = "localhost";
    private $db = "contacts_api";
    private $user = "root";
    private $pass = "";

    public function connect()
    {
        return new PDO(
            "mysql:host=$this->host;dbname=$this->db;charset=utf8",
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
