<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password_hash;
    private $created_at;

    public function __construct(string $username, string $email, string $password) {
        $this->id = uniqid('user_', true);
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'password_hash' => $this->password_hash,
            'created_at' => $this->created_at
        ];
    }

    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getEmail() {
        return $this->email;
    }
}

?>