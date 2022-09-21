<?php

class Item
{
    public string $guid;
    public string $name;
    public int $rating;
    public array $aliases;
    public array $relatedItems;

    private bool $isInitialized = false;

    public function constructFromValues(string $guid, string $name, int $rating, array $aliases, array $relatedItems): void
    {
        $this->guid = $guid;
        $this->name = $name;
        $this->rating = $rating;
        $this->aliases = $aliases;
        $this->relatedItems = $relatedItems;
        $this->isInitialized = true;
    }

    public function isInitialized(): bool
    {
        return $this->isInitialized;
    }
}