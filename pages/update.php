<?php
require_once "controllers/ItemController.php";

$guid = $_POST["guid"] ?? "";
$item = getByGuid($guid);

$name = $_POST["name"] ?? "";
$rating = $_POST["rating"] ?? "";
$aliases = $_POST["aliases"] ?? "";
$relatedItems = $_POST["related-items"] ?? "";

if (empty($item)) {
    echo "<h1>The provided GUID is invalid or there's no item matching that GUID.</h1>";
    return;
}

if (!(empty($name) && empty($rating) && empty($aliases) && empty($relatedItems))) {
    $item = new Item($guid, $name, $rating, $aliases, $relatedItems);
    $isSuccess = tryUpdate($guid, $item);
}
?>

<form action="#" method="post">
  <label>
    Guid
    <input type="text" name="guid" value="<?php echo $item->guid ?>" readonly>
  </label>

  <label>
    Name
    <input type="text" name="name" value="<?php echo $item->name ?>">
  </label>

  <label>
    Aliases
    <input type="text" name="aliases" value="<?php echo implode(",", $item->aliases) ?>">
  </label>

  <label>
    Related Items
    <input type="text" name="related-items" value="<?php echo implode(",", $item->relatedItems) ?>">
  </label>

  <label>
    Rating
    <input type="number" name="rating" value="<?php echo $item->rating ?>">
  </label>

  <label hidden>
    <input type="text" name="success" value="<?php echo $isSuccess ?? 0 ?>">
  </label>

  <button type="submit" name="action" value="update">Commit!</button>
</form>
