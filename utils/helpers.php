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
