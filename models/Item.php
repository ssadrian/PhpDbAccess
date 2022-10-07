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
        $this->guid = trim($guid ?? GUID());
        $this->name = trim($name ?? "");
        $this->rating = $rating;
        $this->aliases = empty(trim($aliases)) ? [] : explode(",", trim($aliases));
        $this->relatedItems = empty(trim($relatedItems)) ? [] : explode(",", trim($relatedItems));
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