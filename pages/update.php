<?php
require_once "utils/helpers.php";
require_once "controllers/ItemController.php";

$itemController = ItemController::getInstance();

$guid = $_POST["guid"] ?? "";
$item = getPurifiedItem($itemController->getByGuid($guid));

$name = $_POST["name"] ?? "";
$rating = $_POST["rating"] ?? "";
$price = $_POST["price"] ?? "";
$aliases = $_POST["aliases"] ?? "";
$relatedItems = $_POST["related-items"] ?? "";

if (empty($item)) {
    echo "<h1>The provided GUID is invalid or there's no item matching that GUID.</h1>";
    return;
}

if (!(empty($name) && empty($rating) && empty($price) && empty($aliases) && empty($relatedItems))) {
    $item = getPurifiedItem(new Item($name, $rating, $price, $aliases, $relatedItems, $guid));
    $isSuccess = $itemController->tryUpdate($guid, $item);
}
?>

<form action="#" method="post">
  <div class="row mb-3">
    <label for="edit-guid" class="col-sm-2 col-form-label">Guid</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-guid" class="form-control" name="guid" value="<?php echo $item->guid ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit-name" class="col-sm-2 col-form-label">Name</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-name" class="form-control" name="name" value="<?php echo $item->name ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit-aliases" class="col-sm-2 col-form-label">Aliases</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-aliases" class="form-control" name="aliases" value="<?php echo implode(",", $item->aliases) ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit-related-items" class="col-sm-2 col-form-label">Related Items</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-related-items" class="form-control" name="related-items" value="<?php echo implode(",", $item->relatedItems) ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit-rating" class="col-sm-2 col-form-label">Rating</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-rating" class="form-control" type="number" name="rating" value="<?php echo $item->rating ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit-price" class="col-sm-2 col-form-label">Price</label>

    <div class="col-sm-10 mb-3">
      <input id="edit-price" class="form-control" type="number" name="price" value="<?php echo $item->price ?>">
    </div>
  </div>

  <label hidden>
    <input name="success" value="<?php echo $isSuccess ?? 0 ?>">
  </label>

  <button class="btn btn-outline-dark" type="submit" name="action" value="update">Commit!</button>
</form>
