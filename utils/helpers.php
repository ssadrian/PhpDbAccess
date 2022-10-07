<?php

require_once "sanitizer.php";
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

    foreach($array as $element) {
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
