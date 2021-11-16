<?php

class UserModel
{
    private string $userName;
    private string $email;
    private string $password;
    private string $avatar;

    public function __construct($userName, $email, $password, $avatar) {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}
