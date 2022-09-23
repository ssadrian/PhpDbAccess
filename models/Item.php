<?php

class Item
{
    public string $guid;
    public string $name;
    public int $rating;
    public array $aliases;
    public array $relatedItems;

    public function __construct(
        string  $guid,
        ?string $name,
        ?int    $rating,
        ?string $aliases,
        ?string $relatedItems)
    {
        $this->guid = $guid;
        $this->name = $name ?? "";
        $this->rating = $rating ?? 0;
        $this->aliases = empty($aliases) ? [] : explode(",", $aliases);
        $this->relatedItems = empty($relatedItems) ? [] : explode(",", $relatedItems);
    }

    function isInitialized(): bool
    {
        return !(
            empty($this->guid) &&
            empty($this->name) &&
            empty($this->rating) &&
            empty($this->aliases) &&
            empty($this->relatedItems)
        );
    }

    function hasSimilaritiesWith(Item $other): bool
    {
        return
            str_contains($this->guid, $other->guid) ||
            str_contains($this->name, $other->name) ||
            $this->rating === $other->rating ||
            str_contains(implode("", $this->aliases), implode("", $other->aliases)) ||
            str_contains(implode("", $this->relatedItems), implode("", $other->relatedItems));
    }
}