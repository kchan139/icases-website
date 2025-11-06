<?php
class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($email, $password, $fullName)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO {$this->table} (email, password, full_name)
                  VALUES (:email, :password, :full_name)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":full_name", $fullName);

        return $stmt->execute();
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM $this->table WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email)
    {
        $query = "SELECT id FROM {$this->table}  WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function createResetToken($email)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $query = "UPDATE {$this->table} SET reset_token = :token, reset_token_expiry = :expiry
                  WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":expiry", $expiry);
        $stmt->bindParam(":email", $email);

        return $stmt->execute() ? $token : false;
    }

    public function verifyResetToken($token)
    {
        $query = "SELECT * FROM {$this->table}
                  WHERE reset_token = :token
                  AND reset_token_expiry > NOW()
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function resetPassword($token, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE {$this->table}
                  SET password = :password, reset_token = NULL, reset_token_expiry = NULL
                  WHERE reset_token = :token";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":token", $token);

        return $stmt->execute();
    }
}
