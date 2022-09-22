<?php

require_once "controllers/ItemController.php";
require_once "models/Item.php";

$guid = GUID();
$name = $_POST["name"] ?? "";
$rating = $_POST["rating"] ?? 0;
$aliases = $_POST["aliases"] ?? "";
$relatedItems = $_POST["relatedItems"] ?? "";

function GUID(): string
{
    if (function_exists('com_create_guid')) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

if (!(empty($name) || empty($aliases) || empty($relatedItems))) {
    $isSuccessful = tryCreate(new Item($guid, $name, $rating, $aliases, $relatedItems));
}
?>

<form action="#" method="post">
  <label>
    Name:
    <input type="text" name="name">
  </label>

  <label>
    Aliases:
    <input type="text" name="aliases">
  </label>

  <label>
    Related Items:
    <input type="text" name="relatedItems">
  </label>

  <label>
    Rating:
    <input type="number" name="rating" min="0" max="5" value="0">
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
  </label>

  <button type="submit" name="action" value="create">Create!</button>
</form>
