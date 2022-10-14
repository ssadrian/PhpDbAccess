<?php

session_start();
$isAdmin = $_SESSION["isAdmin"] ?? false;
$isLogged = $_SESSION["isLogged"] ?? false;

?>

<header>
  <nav class="navbar navbar-expand-lg">
    <div class="container-flow">
      <form action="../index.php" method="post">
        <button class="btn" type="submit" name="action" value="landpage"><i class="bi bi-cup-hot-fill"></i></button>
        <button class="btn" type="submit" name="action" value="read">Listing</button>
          <?php
          if ($isLogged) {
              echo '<button class="btn" type="submit" name="action" value="create">Create</button>';
              echo '<button class="btn" type="submit" name="action" value="logout">Logout</button>';
          } else {
              echo '<button class="btn" type="submit" name="action" value="login">Login</button>';
              echo '<button class="btn" type="submit" name="action" value="signup">Signup</button>';
          }
          ?>
      </form>
    </div>
  </nav>
  <hr>
</header>
