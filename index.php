<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="stylesheet" href="public/assets/libs/bootstrap-5.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="public/assets/libs/bootstrap-icons-1.9.1/bootstrap-icons.css">

  <link rel="stylesheet" href="public/assets/css/index.css">

  <title>Document</title>
</head>

<body>
    <?php require_once "components/header.html"; ?>

  <div class="content">
      <?php
      $action = trim($_POST["action"] ?? "landpage") . ".php";
      $allowedActions = ["landpage", "create", "read", "update", "delete", "error"];
      $allowedPages = [];

      foreach (new DirectoryIterator("./pages") as $page) {
          $pageName = $page->getFilename();
          $allowedPages[] = $pageName;
      }

      if (array_search($action, $allowedPages)) {
          require_once "pages/" . $action;
      } else {
          require_once "pages/error.php";
      }
      ?>
  </div>

    <?php require_once "components/footer.html"; ?>
</body>
</html>