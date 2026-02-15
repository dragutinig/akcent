<?php

class Database
{
    private $host = '127.0.0.1'; // Vaš hostname za MySQL bazu
    private $username = 'root'; // Vaše korisničko ime za bazu
    private $password = ''; // Vaša lozinka za bazu
    private $dbname = 'akcentrs'; // Ime baze podataka
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
