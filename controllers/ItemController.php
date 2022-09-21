<?php

require_once "db.php";
require_once "models/Item.php";

function getAll(): array
{
    global $db;
    $qry = "SELECT guid, name, rating, aliases, related_items FROM items";
    $results = $db->query($qry);

    if ($results === false) {
        return array();
    }

    $allItems = array();
    foreach ($results->fetch_all(MYSQLI_ASSOC) as $result) {
        $item = new Item();
        $item->constructFromValues(
            $result["guid"],
            $result["name"],
            $result["rating"],
            explode(",", $result["aliases"]),
            explode(",", $result["related_items"])
        );
        $allItems[] = $item;
    }

    return $allItems;
}

function getByGuid(string $guid): ?Item
{
    $qry = "SELECT guid, name, rating, aliases, related_items FROM items WHERE guid=?";
    $stmt = createPreparedStatement($qry);

    if ($stmt === false) {
        return null;
    }

    $stmt->execute([$guid]);

    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    for ($i = 0; $i < sizeof($results); $i++) {
        $result = $results[$i];

        $item = new Item();
        $item->constructFromValues(
            $result["guid"],
            $result["name"],
            $result["rating"],
            explode(",", $result["aliases"]),
            explode(",", $result["related_items"])
        );

        return $item;
    }

    return null;
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

function tryUpdate(string $guid, Item $newItem): bool
{
    $oldItem = getByGuid($guid);

    if ($oldItem === null) {
        return false;
    }

    $qry = "UPDATE items SET name = ?, rating = ?, aliases = ?, related_items = ? WHERE guid = ?";
    $stmt = createPreparedStatement($qry);

    if ($stmt === false) {
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

    if ($stmt === false) {
        return false;
    }

    return $stmt->execute([$guid]);
}

function getNonEmptyValue($a, $b)
{
    return empty($a) ? $b : $a;
}
