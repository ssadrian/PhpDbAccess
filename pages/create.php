<?php

require_once "utils/helpers.php";

require_once "controllers/ItemController.php";
require_once "models/Item.php";

$itemController = ItemController::getInstance();

$name = $_POST["name"] ?? "";
$rating = $_POST["rating"] ?? 0;
$price = $_POST["rating"] ?? 0;
$aliases = $_POST["aliases"] ?? "";
$relatedItems = $_POST["related-items"] ?? "";

if (!(empty($name) && empty($rating))) {
    $dirtyItem = new Item($name, $rating, $price, $aliases, $relatedItems, null);
    $isSuccessful = $itemController->tryCreate(getPurifiedItem($dirtyItem));
}
?>

<form class="" action="#" method="post">
  <div class="row mb-3">
    <label for="input-name" class="col-sm-2 col-form-label">Name</label>

    <div class="col-sm-10">
      <input id="input-name" class="form-control" name="name" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="input-aliases" class="col-sm-2 col-form-label">Aliases</label>

    <div class="col-sm-10">
      <input id="input-aliases" class="form-control" name="aliases">
    </div>
  </div>

  <div class="row mb-3">
    <label for="input-related-items" class="col-sm-2 col-form-label">Related Items</label>

    <div class="col-sm-10">
      <input id="input-related-items" class="form-control" name="related-items">
    </div>
  </div>

  <div class="row mb-3">
    <label for="input-rating" class="col-sm-2 col-form-label">Rating</label>

    <div class="col-sm-10">
      <input id="input-rating" class="form-control" type="number" name="rating" min="0" value="0" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="input-price" class="col-sm-2 col-form-label">Price</label>

    <div class="col-sm-10">
      <input id="input-price" class="form-control" type="number" name="price" min="0" value="0" required>
    </div>
  </div>

  <button class="btn btn-outline-dark" type="submit" name="action" value="create">Create!</button>
  <button class="btn btn-outline-dark" type="reset">Reset</button>
</form>
