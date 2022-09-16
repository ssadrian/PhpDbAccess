<?php

class Item
{
    public string $guid = "";
    public string $name = "";
    public int $rating = 0;
    public array $aliases = array();
    public array $relatedItems = array();

    public function __constructor($mysql_result): void
    {
        $this->guid = $mysql_result["guid"];
        $this->name = $mysql_result["name"];
        $this->rating = $mysql_result["rating"];
        $this->aliases = $mysql_result["aliases"];
        $this->relatedItems = $mysql_result["related_items"];
    }

    public function constructFromValues(string $guid, string $name, int $rating, array $aliases, array $relatedItems): void {
        $this->guid = $guid;
        $this->name = $name;
        $this->rating = $rating;
        $this->aliases = $aliases;
        $this->relatedItems = $relatedItems;
    }
}