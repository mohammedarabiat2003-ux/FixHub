<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($name, $email, $password, $role = 'customer')
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES (:name, :email, :password, :role)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $passwordHash,
            ":role" => $role
        ]);
    }

    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        return $stmt->fetch() ? true : false;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

