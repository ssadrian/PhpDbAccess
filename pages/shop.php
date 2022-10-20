<?php

require_once "utils/helpers.php";

if (!($_SESSION["isLogged"] ?? false)) {
    returnToLandPage();
}

require_once "controllers/UserController.php";
$userController = UserController::getInstance();

require_once "controllers/ItemController.php";
$itemController = ItemController::getInstance();

$credentials = $_SESSION["credentials"] ?? "";

$itemGuid = $_POST["guid"] ?? "";
$userGuid = explode(":", $credentials)[0];

$pageAction = $_POST["page-action"] ?? "";

if (!empty($itemGuid)) {
    switch ($pageAction) {
        case "add":
            $userController->tryAddItemToCart($itemGuid, $userGuid);
            break;
        case "remove":
            $userController->tryRemoveItemFromCart($itemGuid, $userGuid);
            break;
        default:
            break;
    }

    returnToLandPage();
}

$filterGuid = $_POST["filter-guid"] ?? "";
$filterName = $_POST["filter-name"] ?? "";
$filterRating = $_POST["filter-rating"] ?? -1;
$filterPrice = $_POST["filter-price"] ?? -1;
$filterAlias = $_POST["filter-aliases"] ?? "";
$filterRelatedItem = $_POST["filter-related-items"] ?? "";

if ($filterRating === "") {
    $filterRating = -1;
}

if ($filterPrice === "") {
    $filterPrice = -1;
}

$filterItem = getPurifiedItem(
    new Item($filterName, $filterRating, $filterPrice, $filterAlias, $filterRelatedItem, $filterGuid)
);

$allItems = $filteredItems = $userController->getCartItems($userGuid);
if ($filterItem->isInitialized()) {
    $filteredItems = $itemController->getFiltered($filterItem);
}

$totalPrice = 0;

?>

<form id="filter-form" action="#" method="post"></form>

<table class="table table-hover">
  <thead>
    <tr>
      <th>
        #
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (Item $item) {
                  return $item->guid;
              }, $allItems),
              "Guid",
              $filterGuid
          );
          ?>
      </th>

      <th>
          <?php
          createDropdownTextSelector(
              array_map(function (Item $item) {
                  return $item->name;
              }, $allItems),
              "Name",
              $filterName
          );
          ?>
      </th>

      <th>
          <?php
          $aliases = [];

          foreach ($allItems as $item) {
              foreach ($item->aliases as $alias) {
                  $aliases[] = $alias;
              }
          }

          createDropdownTextSelector(
              $aliases,
              "Aliases",
              $filterAlias
          );
          ?>
      </th>

      <th>
          <?php
          $relatedItems = [];

          foreach ($allItems as $item) {
              foreach ($item->relatedItems as $relatedItem) {
                  $relatedItems[] = $relatedItem;
              }
          }

          createDropdownTextSelector(
              $relatedItems,
              "Related Items",
              $filterRelatedItem
          );
          ?>
      </th>

      <th>
        <label class="range-label">
          Rating <span class="range-output"></span>
          <input type="range" class="form-range" name="filter-rating" min="-1" max="5"
                 value="<?php echo $filterItem->rating; ?>"
                 form="filter-form">
          <datalist id="data-rating">
              <?php
              foreach (range(0, 5) as $count) {
                  echo "<option value='$count'></option>";
              }
              ?>
          </datalist>
        </label>
      </th>

      <th>
        <label class="range-label">
          Price <span class="range-output"></span>
          <input type="range" class="form-range" name="filter-price" min="-1" max="1000"
                 value="<?php echo $filterItem->price; ?>"
                 form="filter-form">
          <datalist id="data-price">
              <?php
              foreach (range(0, 1000) as $count) {
                  echo "<option value='$count'></option>";
              }
              ?>
          </datalist>
        </label>
      </th>

      <th class="text-center">
        <button class="btn btn-outline-primary"
                type="submit" form="filter-form" name="action" value="read">
          Apply
        </button>
      </th>
    </tr>
  </thead>

  <tbody>
      <?php
      foreach ($filteredItems as $count => $dirtyItem) {
          $item = getPurifiedItem($dirtyItem);
          $totalPrice += $item->price;

          echo "<tr>";
          echo "<td>$count</td>";
          echo "<td>$item->guid</td>";
          echo "<td>$item->name</td>";
          echo "<td>" . implode(", ", $item->aliases) . "</td>";
          echo "<td>" . implode(", ", $item->relatedItems) . "</td>";
          echo "<td>$item->rating</td>";
          echo "<td>$item->price</td>";
          echo "<td class='text-center'>
<form action='#' method='post'>
    <input type='text' name='guid' value='" . $item->guid . "' hidden>
    <input type='text' name='page-action' value='add' hidden>

    <button class='btn btn-dark' type='submit' name='action' value='shop'>Add another</button>
</form>
<form action='#' method='post'>
    <input type='text' name='guid' value='" . $item->guid . "' hidden>
    <input type='text' name='page-action' value='remove' hidden>

    <button class='btn btn-outline-danger' type='submit' name='action' value='shop'>Delete</button>
</form>
";
          echo "</tr>";
      }
      ?>

    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th class="text-center">Total:</th>
      <th><?php echo $totalPrice; ?>$</th>
    </tr>
  </tbody>
</table>

