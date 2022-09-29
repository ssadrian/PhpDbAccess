<?php

require_once "utils/sanitizer.php";
require_once "utils/helpers.php";

class Item
{
    public string $guid;
    public string $name;
    public ?int $rating;
    public array $aliases;
    public array $relatedItems;

    public function __construct(
        ?string $name,
        ?int    $rating,
        ?string $aliases,
        ?string $relatedItems,
        ?string $guid)
    {
        $this->guid = trim($guid ?? Item::GUID());
        $this->name = trim($name ?? "");
        $this->rating = $rating;
        $this->aliases = empty(trim($aliases)) ? [] : explode(",", trim($aliases));
        $this->relatedItems = empty(trim($relatedItems)) ? [] : explode(",", trim($relatedItems));
    }

    static function GUID(): string
    {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151),
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    function isInitialized(): bool
    {
        return !(
            empty($this->guid) &&
            empty($this->name) &&
            ($this->rating === null || $this->rating === -1) &&
            empty($this->aliases) &&
            empty($this->relatedItems)
        );
    }

    function hasSimilaritiesWith(Item $other): bool
    {
        if (!empty($other->guid) && str_contains($this->guid, $other->guid)) {
            return true;
        }

        if (!empty($other->name) && str_contains($this->name, $other->name)) {
            return true;
        }

        if ($other->rating != -1 && ($this->rating === $other->rating)) {
            return true;
        }

        if (!empty($other->aliases)) {
            foreach ($other->aliases as $alias) {
                if (hasArrayAnySimilarValue($this->aliases, $alias)) {
                    return true;
                }
            }
        }

        if (!empty($other->relatedItems)) {
            foreach ($other->relatedItems as $relatedItem) {
                if (hasArrayAnySimilarValue($this->relatedItems, $relatedItem)) {
                    return true;
                }
            }
        }

        return false;
    }
}