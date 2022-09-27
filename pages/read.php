<?php
require_once "utils/sanitizer.php";

require_once "controllers/ItemController.php";
require_once "models/Item.php";

$filterGuid = $_POST["filter-guid"] ?? "";
$filterName = $_POST["filter-name"] ?? "";
$filterRating = intval($_POST["filter-rating"] ?? null);
$filterAlias = $_POST["filter-alias"] ?? "";
$filterRelatedItem = $_POST["filter-related-item"] ?? "";

$filterItem = new Item($filterName, $filterRating, $filterAlias, $filterRelatedItem, $filterGuid);

if ($filterItem->isInitialized()) {
    $items = getFiltered($filterItem);
} else {
    $items = getAll();
}

function createOptionsFromValues(array $optionValues, mixed $valueToSelect = null): void
{
    global $purifier;
    $echoedValues = [];

    $valueToSelect = $purifier->purify($valueToSelect);

    echo "<option value=''></option>";
    foreach ($optionValues as $optionValue) {
        $optionValue = $purifier->purify($optionValue);

        if (trim($optionValue) === "" || in_array($optionValue, $echoedValues)) {
            continue;
        }

        if ($valueToSelect == $optionValue) {
            echo "<option value='$optionValue' selected>$optionValue</option>";
        } else {
            echo "<option value='$optionValue'>$optionValue</option>";
        }

        $echoedValues[] = $optionValue;
    }
}

?>

<form id="filter-form" action="#" method="post"></form>

<table class="table table-hover">
    <thead>
        <tr>
            <th>
                # <i class="bi bi-sort-up disabled"></i>
            </th>

            <th>
                <label>
                    Guid
                    <select name="filter-guid" form="filter-form">
                        <?php
                        createOptionsFromValues(array_map(function (Item $item) {
                            return $item->guid;
                        }, $items), $filterGuid);
                        ?>
                    </select>
                </label>

                <i class="bi bi-sort-up disabled"></i>
            </th>

            <th>
                <label>
                    Name
                    <select name="filter-name" form="filter-form">
                        <?php
                        createOptionsFromValues(array_map(function (Item $item) {
                            return $item->name;
                        }, $items), $filterName);
                        ?>
                    </select>

                    <i class="bi bi-sort-up disabled"></i>
                </label>
            </th>

            <th>
                <label>
                    Aliases
                    <select name="filter-alias" form="filter-form">
                        <?php
                        createOptionsFromValues(array_map(function (Item $item) {
                            return implode(",", $item->aliases);
                        }, $items), $filterAlias);
                        ?>
                    </select>

                    <i class="bi bi-sort-up disabled"></i>
                </label>
            </th>

            <th>
                <label>
                    Related Items
                    <select name="filter-related-item" form="filter-form">
                        <?php
                        createOptionsFromValues(array_map(function (Item $item) {
                            return implode(",", $item->relatedItems);
                        }, $items), $filterRelatedItem);
                        ?>
                    </select>

                    <i class="bi bi-sort-up disabled"></i>
                </label>
            </th>

            <th>
                <label>
                    Rating
                    <select name="filter-rating" form="filter-form">
                        <?php
                        createOptionsFromValues(array_map(function (Item $item) {
                            return $item->rating;
                        }, $items), $filterRating);
                        ?>
                    </select>

                    <i class="bi bi-sort-up disabled"></i>
                </label>
            </th>

            <th>
                <button class="btn btn-outline-primary"
                        type="submit" form="filter-form" name="action" value="read">Apply filters
                </button>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php
        for ($count = 0; $count < sizeof($items); $count++) {
            $dirtyItem = $items[$count];
            $item = $dirtyItem->getAsPurifiedItem();

            echo "<tr>";
            echo "<td>$count</td>";
            echo "<td>$item->guid</td>";
            echo "<td>$item->name</td>";
            echo "<td>" . implode(", ", $item->aliases) . "</td>";
            echo "<td>" . implode(", ", $item->relatedItems) . "</td>";
            echo "<td>$item->rating</td>";
            echo "<td>
<form action='#' method='post'>
    <input type='text' name='guid' value='" . $dirtyItem->guid . "' hidden>

    <button class='btn btn-outline-dark' type='submit' name='action' value='update'>Edit</button>
    <button class='btn btn-outline-danger' type='submit' name='action' value='delete'>Delete</button>
</form>
";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
