<?php

class Database
{
     private $host = 'localhost'; // Vaš hostname za MySQL bazu
    private $username = 'akcentrs_blogdatabase'; // Vaše korisničko ime za bazu
    private $password = 'Dragigagi1'; // Vaša lozinka za bazu
    private $dbname = 'akcentrs_blogdatabase'; // Ime baze podataka
    private $conn;

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }

        return $this->conn;
    }
}