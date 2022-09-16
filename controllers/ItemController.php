<?php

require_once "db.php";
require_once "models/Item.php";

function getAll(): array
{
    $defaultReturn = array();
    $defaultItem = new Item();
    $defaultItem->guid = 0;
    $defaultItem->name = "None";
    $defaultItem->relatedItems = array("None");
    $defaultItem->aliases = array("None");
    $defaultItem->rating = 0;
    $defaultReturn[] = $defaultItem;

    global $db;
    $qry = "SELECT guid, name, rating, aliases, related_items FROM items";
    $results = $db->query($qry);

    if ($results === false) {
        return $defaultReturn;
    }

    $allItems = array();
    foreach ($results->fetch_all() as $result) {
        $item = new Item($result);
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

    $result = $stmt->get_result();

    if ($result === false) {
        return null;
    }

    return new Item($result);
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

function update($guid, $item)
{

}

function delete($guid)
{

}

