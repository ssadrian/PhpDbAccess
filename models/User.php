<?php

require_once "utils/helpers.php";

class User
{
    public string $guid;
    public string $name;
    public ?string $surname;
    public string $username;
    public string $email;
    public string $password;

    public function __construct(
        ?string $name,
        ?string $surname,
        ?string $username,
        ?string $email,
        ?string $password,
        ?string $guid)
    {
        $this->guid = empty($guid) ? "" : $guid;
        $this->name = empty($name) ? "" : $name;
        $this->surname = empty($surname) ? "" : $surname;
        $this->username = empty($username) ? "" : $username;
        $this->email = empty($email) ? "" : $email;
        $this->password = empty($password) ? "" : $password;
    }

    function isInitialized(): bool {
        return !(
            empty($this->name) &&
            empty($this->surname) &&
            empty($this->username) &&
            empty($this->email) &&
            empty($this->password)
        );
    }
}