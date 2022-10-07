<?php

require_once "database/db.php";
require_once "models/Item.php";
require_once "utils/helpers.php";

class ItemController extends BaseController
{
    public static function getInstance()
    {
        if (empty($instance)) {
            $instance = new ItemController();
        }

        return $instance;
    }

    function getAll(): array
    {
        global $db;
        $qry = "SELECT guid, name, rating, aliases, related_items FROM items";
        $results = $db->query($qry);

        if (empty($results)) {
            return array();
        }

        $allItems = array();
        foreach ($results->fetch_all(MYSQLI_ASSOC) as $result) {
            $allItems[] = new Item(
                $result["name"], intval($result["rating"]), $result["aliases"], $result["related_items"], $result["guid"]
            );
        }

        return $allItems;
    }

    function getByGuid(?string $guid): ?Item
    {
        if (empty($guid)) {
            return null;
        }

        $qry = "SELECT guid, name, rating, aliases, related_items FROM items WHERE guid=?";
        $stmt = createPreparedStatement($qry);

        if ($stmt === false) {
            return null;
        }

        $stmt->execute([$guid]);
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0];

        if (empty($result)) {
            return null;
        }

        return new Item(
            $result["name"], $result["rating"], $result["aliases"], $result["related_items"], $result["guid"]
        );
    }

    function getFiltered(mixed $filterItem): array
    {
        $allItems = $this->getAll();
        return array_filter($allItems, function (Item $item) use ($filterItem): bool {
            return $item->hasSimilaritiesWith($filterItem);
        });
    }

    function tryCreate(mixed $item): bool
    {
        if (!$item->isInitialized()) {
            return false;
        }

        $qry = "INSERT INTO items (guid, name, rating, aliases, related_items) VALUE (?, ?, ?, ?, ?);";
        $stmt = createPreparedStatement($qry);

        $aliases = implode(",", $item->aliases);
        $relatedItems = implode(",", $item->relatedItems);

        $stmt->bind_param("ssiss",
            $item->guid, $item->name, $item->rating, $aliases, $relatedItems);

        return $stmt->execute();
    }

    function tryUpdate(?string $guid, mixed $newItem): bool
    {
        $oldItem = $this->getByGuid($guid);

        if (empty($oldItem) || empty($newItem) || !$newItem->isInitialized()) {
            return false;
        }

        $qry = "UPDATE items SET name = ?, rating = ?, aliases = ?, related_items = ? WHERE guid = ?";
        $stmt = createPreparedStatement($qry);

        if (empty($stmt)) {
            return false;
        }

        $name = getNonEmptyValue($newItem->name, $oldItem->name);
        $rating = getNonEmptyValue($newItem->rating, $oldItem->rating);
        $aliases = getNonEmptyValue(
            implode(",", $newItem->aliases), implode(",", $oldItem->aliases));
        $relatedItems = getNonEmptyValue(
            implode(",", $newItem->relatedItems), implode(",", $oldItem->relatedItems));

        $stmt->bind_param("sisss", $name, $rating, $aliases, $relatedItems, $guid);
        return $stmt->execute();
    }

    function tryDelete($guid): bool
    {
        $qry = "DELETE FROM items WHERE guid = ?";
        $stmt = createPreparedStatement($qry);

        if (empty($stmt)) {
            return false;
        }

        return $stmt->execute([$guid]);
    }
}