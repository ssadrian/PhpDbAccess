<?php
require_once "controllers/ItemController.php";
require_once "models/Item.php";

$filterGuid = $_POST["filter-guid"] ?? "";
$filterName = $_POST["filter-name"] ?? "Item2";
$filterRating = $_POST["filter-rating"] ?? 0;
$filterAliases = $_POST["filter-aliases"] ?? "";
$filterRelatedItems = $_POST["filter-related_items"] ?? "";

$filterItem = new Item($filterGuid, $filterName, $filterRating, $filterAliases, $filterRelatedItems);
$items = getFiltered($filterItem);

?>

<table class="table table-hover">
  <thead>
    <tr>
      <th>
        # <i class="bi bi-sort-up disabled"></i>
      </th>
      <th>
        <label>
          Guid
          <select name="filter-guid">
            <option value="" selected>All</option>
            <option value="123">123</option>
            <option value="456">456</option>
          </select>
        </label>

        <i class="bi bi-sort-up disabled"></i>
      </th>
      <th>
        <label>
          Name
          <select name="filter-guid">
            <option value="" selected>All</option>
            <option value="123">123</option>
            <option value="456">456</option>
          </select>

          <i class="bi bi-sort-up disabled"></i>
        </label>
      </th>
      <th>
        <label>
          Aliases
          <select name="filter-guid">
            <option value="" selected>All</option>
            <option value="123">123</option>
            <option value="456">456</option>
          </select>

          <i class="bi bi-sort-up disabled"></i>
        </label>
      </th>
      <th>
        <label>
          Related Items
          <select name="filter-guid">
            <option value="" selected>All</option>
            <option value="123">123</option>
            <option value="456">456</option>
          </select>

          <i class="bi bi-sort-up disabled"></i>
        </label>
      </th>
      <th>
        <label>
          Rating
          <select name="filter-guid">
            <option value="" selected>All</option>
            <option value="123">123</option>
            <option value="456">456</option>
          </select>

          <i class="bi bi-sort-up disabled"></i>
        </label>
      </th>
      <th>
        <button class="btn btn-outline-primary" disabled>Clear filters</button>
      </th>
    </tr>
  </thead>

  <tbody>
      <?php
      for ($count = 0; $count < sizeof($items); $count++) {
          $item = $items[$count];

          echo "<tr>";
          echo "<td>" . $count . "</td>";
          echo "<td>" . $item->guid . "</td>";
          echo "<td>" . $item->name . "</td>";
          echo "<td>" . implode(", ", $item->aliases) . "</td>";
          echo "<td>" . implode(", ", $item->relatedItems) . "</td>";
          echo "<td>" . $item->rating . "</td>";
          echo "<td>
<form action='#' method='post'>
    <input type='text' name='guid' value='" . $item->guid . "' hidden>

    <button class='btn btn-outline-dark' type='submit' name='action' value='update'>Edit</button>
    <button class='btn btn-outline-danger' type='submit' name='action' value='delete'>Delete</button>
</form>
";
          echo "</tr>";
      }
      ?>
  </tbody>
</table>
