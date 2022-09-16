<?php
require_once "controllers/ItemController.php";
require_once "models/Item.php";
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Guid</th>
            <th>Name</th>
            <th>Aliases</th>
            <th>Related Items</th>
            <th>Rating</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $count = 0;
        foreach (getAll() as $_ => $item) {
            echo "<tr>";
            echo "<td>" . $count . "</td>";
            echo "<td>" . $item->guid . "</td>";
            echo "<td>" . $item->name . "</td>";
            echo "<td>" . implode(", ", $item->aliases) . "</td>";
            echo "<td>" . implode(", ", $item->relatedItems) . "</td>";
            echo "<td>" . $item->rating . "</td>";
            echo "</tr>";
            $count++;
        }
        ?>
    </tbody>
</table>
