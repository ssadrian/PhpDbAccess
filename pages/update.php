<?php
require_once "controllers/ItemController.php";

$guid = $_GET["guid"] ?? "";
$item = getByGuid($guid);

if (empty($item)) {
    echo "<h1>The provided GUID is invalid or there's no item matching that GUID.</h1>";
    return;
}
?>

<form action="#" method="post">
  <label>
    Guid
    <input type="text" name="guid" disabled>
  </label>

  <label>
    Name
    <input type="text" name="name">
  </label>

  <label>
    Aliases
    <input type="text" name="aliases">
  </label>

  <label>
    Related Items
    <input type="text" name="related-items">
  </label>

  <label>
    Rating
    <input type="number" name="rating">
  </label>
</form>
