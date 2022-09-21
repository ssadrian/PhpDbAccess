<?php

class Item
{
    public string $guid;
    public string $name;
    public int $rating;
    public array $aliases;
    public array $relatedItems;

    private bool $isInitialized = false;

    public function constructFromValues(
        string $guid,
        string $name = null,
        int    $rating = null,
        array  $aliases = null,
        array  $relatedItems = null): void
    {
        $this->guid = $guid;
        $this->name = $name ?? "";
        $this->rating = $rating ?? 0;
        $this->aliases = $aliases ?? [];
        $this->relatedItems = $relatedItems ?? [];
        $this->isInitialized = true;
    }

    public function isInitialized(): bool
    {
        return $this->isInitialized;
    }
}