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

        // Defensive fallback: podržava i slučaj kada config dođe kao numerički niz
        // ili kada nedostaju očekivani ključevi (npr. loš merge na lokalu).
        $this->host = $this->getConfigValue($config, 'host', 0, 'localhost');
        $this->username = $this->getConfigValue($config, 'username', 1, 'root');
        $this->password = $this->getConfigValue($config, 'password', 2, '');
        $this->dbname = $this->getConfigValue($config, 'dbname', 3, 'akcentrs');
    }

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
