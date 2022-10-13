<?php

require_once "sanitizer.php";

function getPurifiedItem($dirtyItem): Item
{
    global $purifier;

    return new Item(
        $purifier->purify($dirtyItem->name),
        intval($dirtyItem->rating),
        implode(",", $purifier->purifyArray($dirtyItem->aliases)),
        implode(",", $purifier->purifyArray($dirtyItem->relatedItems)),
        $purifier->purify($dirtyItem->guid)
    );
}

function getPurifiedUser(User $dirtyUser): User
{
    global $purifier;

    return new User(
        $purifier->purify($dirtyUser->name),
        $purifier->purify($dirtyUser->surname),
        $purifier->purify($dirtyUser->username),
        $purifier->purify($dirtyUser->email),
        $dirtyUser->password,
        $dirtyUser->guid
    );
}

function getCompleteUser(User $user): User {
    return new User(
        $user->name,
        $user->surname,
        $user->username,
        $user->email,
        password_hash($user->password, PASSWORD_ARGON2ID),
        empty($user->guid) ? GUID() : $user->guid
    );
}

function isPasswordStrong(string $pwd): bool
{
    if (strlen($pwd) < 8) {
        return false;
    }

    $isPasswordStrong = true;
    $containsDigits = preg_match('/.*\d.*/x', $pwd);
    $containsSpecialChars = preg_match('/.*\W.*/x', $pwd);
    $containsUppercase = preg_match('/.*[A-Z].*/x', $pwd);

    if (($containsDigits || $containsSpecialChars || $containsUppercase) === false) {
        $isPasswordStrong = false;
    } elseif (strpos(file_get_contents("database/rockyou.txt"), $pwd)) {
        $isPasswordStrong = false;
    }

    return $isPasswordStrong;
}

function hasArrayAnySimilarValue(array $array, mixed $value): bool
{
    if ($value === null) {
        return false;
    }

    $lowerValue = null;
    if (is_string($value)) {
        $lowerValue = strtolower($value);
    }

    if (in_array($value, $array)) {
        return true;
    }

    foreach ($array as $element) {
        if (is_string($element)) {
            $element = strtolower($element);
        }

        if (str_contains($element, $value) ||
            str_contains($element, $lowerValue)) {
            return true;
        }
    }

    return false;
}

function GUID(): string
{
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
        mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
        mt_rand(16384, 20479), mt_rand(32768, 49151),
        mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function getNonEmptyValue($a, $b)
{
    return empty($a) ? $b : $a;
}
