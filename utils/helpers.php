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

function getCompleteUser(User $user): User
{
    $newUser = new User($user->name, $user->surname, $user->username, $user->email, $user->password, empty($user->guid) ? GUID() : $user->guid);

    if ($user->isPasswordEncrypted === false) {
        $newUser->encryptPassword();
    }

    return $newUser;
}

function isPasswordStrong(string $pwd): bool
{
    if (strlen($pwd) < 8) {
        return false;
    }

    $pwdFile = "assets/rockyou.txt";

    $isPasswordStrong = true;
    $containsDigits = preg_match("/.*\d.*/", $pwd);
    $containsSpecialChars = preg_match('/.*\W.*/', $pwd);
    $containsUppercase = preg_match('/.*[A-Z].*/', $pwd);

    if (($containsDigits || $containsSpecialChars || $containsUppercase) === false) {
        $isPasswordStrong = false;
    } elseif (strpos(file_get_contents($pwdFile), $pwd)) {
        $isPasswordStrong = false;
    }

    return $isPasswordStrong;
}

function isUserAdmin(User $user): bool
{
    $adminFile = "assets/admins.json";
    $json = json_decode(file_get_contents($adminFile), true);
    $adminGuids = array();

    foreach ($json as $entry) {
        $adminGuids[] = $entry["guid"];
    }

    return in_array($user->guid, $adminGuids);
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

function startSessionsIfNotExistent(): void
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        return;
    }

    session_start();
}

function createDataListFromValues(array $optionValues, string $listName): void
{
    global $purifier;
    $echoedValues = [];

    echo "<datalist id='$listName'>";
    foreach ($optionValues as $optionValue) {
        $optionValue = $purifier->purify($optionValue);

        if (in_array($optionValue, $echoedValues)) {
            continue;
        }

        echo "<option value='$optionValue'></option>";
        $echoedValues[] = $optionValue;
    }
    echo "</datalist>";
}

function createDropdownTextSelector(
    array  $optionValues,
    string $placeholder = "",
    string $defaultValue = ""): void
{
    global $purifier;

    $defaultValue = $purifier->purify($defaultValue);
    $placeholder = $purifier->purify($placeholder);

    $lowerPlaceholder = str_replace(" ", "-", strtolower($placeholder));
    $inputName = "filter-$lowerPlaceholder";
    $dataListName = "data-$lowerPlaceholder";

    $formName = "filter-form";

    echo "
<label>
  <input name='$inputName' placeholder='$placeholder' list='$dataListName' value='$defaultValue' form='$formName'>
</label>";

    createDataListFromValues($optionValues, $dataListName);
}

function returnToLandPage(): void
{
    header("Location: index.php");
}