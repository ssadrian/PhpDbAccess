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

    public bool $isPasswordEncrypted = false;

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

    function isInitialized(): bool
    {
        return !(
            empty($this->name) &&
            empty($this->surname) &&
            empty($this->username) &&
            empty($this->email) &&
            empty($this->password)
        );
    }

    function hasSimilaritiesWith(User $other): bool
    {
        if (!empty($other->guid) && str_contains($this->guid, $other->guid)) {
            return true;
        }

        if (!empty($other->name) && str_contains($this->name, $other->name)) {
            return true;
        }

        if (!empty($other->surname) && str_contains($this->surname, $other->surname)) {
            return true;
        }

        if (!empty($other->username) && str_contains($this->username, $other->username)) {
            return true;
        }

        if (!empty($other->email) && str_contains($this->email, $other->email)) {
            return true;
        }

        if (!empty($other->password)) {
            $hasSimilarPassword = false;

            switch ([$this->isPasswordEncrypted, $other->isPasswordEncrypted]) {
                case ([false, false]):
                    $hasSimilarPassword =
                        $this->password === $other->password || str_contains($this->password, $other->password);
                    break;
                case ([true, false]):
                    $hasSimilarPassword = password_verify($other->password, $this->password);
                    break;
                case ([false, true]):
                    $hasSimilarPassword = password_verify($this->password, $other->password);
                    break;
                case ([true, true]):
                    $hasSimilarPassword = $this->password === $other->password;
                    break;
            }

            if ($hasSimilarPassword) {
                return true;
            }
        }

        return false;
    }

    public function encryptPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_ARGON2ID);
        $this->isPasswordEncrypted = true;
    }
}