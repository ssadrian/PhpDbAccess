<?php

require_once "db.php";
require_once "models/Item.php";

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
            $result["guid"], $result["name"], $result["rating"], $result["aliases"], $result["related_items"]
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
        $result["guid"], $result["name"], $result["rating"], $result["aliases"], $result["related_items"]
    );
}

function getFiltered(Item $filterItem): array
{
    $allItems = getAll();
    return array_filter($allItems, function ($item) use ($filterItem) {
        return
            $item->guid === $filterItem->guid ||
            $item->name === $filterItem->name ||
            $item->rating === $filterItem->rating ||
            $item->aliases === $filterItem->aliases ||
            $item->relatedItems === $filterItem->relatedItems;
    });
}

function tryCreate(Item $item): bool
{
    $qry = "INSERT INTO items (guid, name, rating, aliases, related_items) VALUE (?, ?, ?, ?, ?);";
    $stmt = createPreparedStatement($qry);

    $aliases = implode(',', $item->aliases);
    $relatedItems = implode(',', $item->relatedItems);

    $stmt->bind_param("ssiss",
        $item->guid, $item->name, $item->rating, $aliases, $relatedItems);

    return $stmt->execute();
}

function tryUpdate(?string $guid, ?Item $newItem): bool
{
    $oldItem = getByGuid($guid);

    if (empty($oldItem)) {
        return false;
    }

    $qry = "UPDATE items SET name = ?, rating = ?, aliases = ?, related_items = ? WHERE guid = ?";
    $stmt = createPreparedStatement($qry);

    if (empty($stmt)) {
        return false;
    }

    $name = getNonEmptyValue($newItem->name, $oldItem->name);
    $rating = getNonEmptyValue($newItem->rating, $oldItem->rating);
    $aliases = getNonEmptyValue(implode(",", $newItem->aliases), implode(",", $oldItem->aliases));
    $relatedItems = getNonEmptyValue(implode(",", $newItem->relatedItems), implode(",", $oldItem->relatedItems));

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

function getNonEmptyValue($a, $b)
{
    return empty($a) ? $b : $a;
}
