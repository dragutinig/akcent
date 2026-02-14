<?php

require_once 'Database.php';

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register()
    {
        $query = "INSERT INTO " . $this->table . " (username, email, password_hash, role) 
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Greška pri pripremi upita: " . $this->conn->error;
            return false;
        }

        // Čišćenje podataka
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password_hash = password_hash($this->password_hash, PASSWORD_BCRYPT); // Sigurna lozinka
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Bind parametri
        $stmt->bind_param('ssss', $this->username, $this->email, $this->password_hash, $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    // Provera da li korisnik sa email-om već postoji
    public function emailExists()
    {
        $query = "SELECT id, username, email, password_hash, role FROM " . $this->table . " WHERE email = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Greška pri pripremi upita: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($this->id, $this->username, $this->email, $this->password_hash, $this->role);
            $stmt->fetch();
            return true;
        }

        return false;
    }

    public function login()
    {
        $query = "SELECT id, username, email, password_hash, role FROM " . $this->table . " WHERE email = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Greška pri pripremi upita: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($this->id, $this->username, $this->email, $db_password_hash, $this->role);
            $stmt->fetch();

            // Provera lozinke
            if (password_verify($this->password_hash, $db_password_hash)) {
                return true;
            } else {
                echo "Lozinka nije tačna.";
                return false;
            }
        } else {
            echo "Korisnik sa ovom email adresom nije pronađen.";
            return false;
        }
    }
}