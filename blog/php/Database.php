<?php

require_once 'config.php';

class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    private function getConfigValue(array $config, string $key, int $fallbackIndex, string $default): string
    {
        if (isset($config[$key]) && $config[$key] !== '') {
            return (string) $config[$key];
        }

        if (isset($config[$fallbackIndex]) && $config[$fallbackIndex] !== '') {
            return (string) $config[$fallbackIndex];
        }

        return $default;
    }

    public function __construct()
    {
        $config = getDbConfig();
        $this->host = $this->getConfigValue($config, 'host', 0, 'localhost');
        $this->username = $this->getConfigValue($config, 'username', 1, 'root');
        $this->password = $this->getConfigValue($config, 'password', 2, '');
        $this->dbname = $this->getConfigValue($config, 'dbname', 3, 'akcentrs');
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error . ' (DB: ' . $this->dbname . ')');
        }

        $this->conn->set_charset('utf8mb4');
        $this->conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

        return $this->conn;
    }
}
