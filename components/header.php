<?php

require_once "../utils/helpers.php";
startSessionsIfNotExistent();

?>

<header>
  <nav class="navbar navbar-expand-lg">
    <form class="d-flex align-items-center" action="index.php" method="post">
      <button class="btn" type="submit" name="action" value="landpage"><i class="bi bi-cup-hot-fill"></i></button>
      <button class="btn" type="submit" name="action" value="read">Listing</button>
        <?php
        if ($_SESSION["isLogged"] ?? false) {
            echo '<button class="btn" type="submit" name="action" value="create">Create</button>';
            echo '<button class="btn" type="submit" name="action" value="shop">Shop</button>';
            echo '<button class="btn" type="submit" name="action" value="logout">Logout</button>';

            if ($_SESSION["isAdmin"] ?? false) {
                echo '<button class="btn" type="submit" name="action" value="users">Users</button>';
            }
        } else {
            echo '<button class="btn" type="submit" name="action" value="login">Login</button>';
            echo '<button class="btn" type="submit" name="action" value="signup">Signup</button>';
        }
        ?>
    </form>
  </nav>
  <hr>
</header>
