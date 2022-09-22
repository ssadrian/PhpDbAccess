<?php

class Item
{
    public string $guid;
    public string $name;
    public int $rating;
    public array $aliases;
    public array $relatedItems;

    private bool $isInitialized = false;

    public function __construct(
        string $guid,
        string $name = null,
        int    $rating = null,
        string  $aliases = null,
        string  $relatedItems = null)
    {
        $this->guid = $guid;
        $this->name = $name ?? "";
        $this->rating = $rating ?? 0;
        $this->aliases = explode(",", $aliases) ?? ["None"];
        $this->relatedItems = explode(",", $relatedItems) ?? ["None"];
        $this->isInitialized = true;
    }

    public function isInitialized(): bool
    {
        return $this->isInitialized;
    }
}