<?php

require_once "utils/HtmlPurifier/HTMLPurifier.auto.php";

require_once "controllers/ItemController.php";
require_once "models/Item.php";

$name = $_POST["name"] ?? "";
$rating = $_POST["rating"] ?? 0;
$aliases = $_POST["aliases"] ?? "";
$relatedItems = $_POST["relatedItems"] ?? "";

if (!(empty($name) && empty($rating))) {
    $dirtyItem = new Item($name, $rating, $aliases, $relatedItems, null);
    $isSuccessful = tryCreate($dirtyItem->getAsPurifiedItem());
}
?>

<form action="#" method="post">
  <label>
    Name:
    <input type="text" name="name" required>
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
    <input type="number" name="rating" min="0" value="0" required>

    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
    <i class="bi bi-star"></i>
  </label>

  <button class="btn btn-outline-dark" type="submit" name="action" value="create">Create!</button>
</form>
