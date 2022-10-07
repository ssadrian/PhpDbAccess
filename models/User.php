<?php

require_once "utils/sanitizer.php";
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
        ?string $guid,
        ?string $name,
        ?string $surname,
        ?string $username,
        ?string $email,
        ?string $password)
    {
        $this->guid = empty($guid) ? GUID() : $guid;
        $this->name = empty($name) ? "" : $name;
        $this->surname = empty($surname) ? "" : $surname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    function isInitialized(): bool {
        return !(
            empty($this->guid) &&
            empty($this->name) &&
            empty($this->surname) &&
            empty($this->username) &&
            empty($this->email) &&
            empty($this->password)
        );
    }
}