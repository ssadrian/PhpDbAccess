<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="https://github.com/ssadrian/PhpDbAccess">
  <meta name="theme-color" content="#EBE7E7"/>

  <link rel="stylesheet" href="libs/bootstrap-5.2.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="libs/bootstrap-icons-1.9.1/bootstrap-icons.css">

  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/create.css">
  <link rel="stylesheet" href="css/landpage.css">

  <title>Fase 3</title>
</head>

<body>
    <?php require_once "../components/header.php"; ?>

  <div class="content">
      <?php
      $action = trim($_POST["action"] ?? "landpage") . ".php";
      $allowedPages = [];

      foreach (new DirectoryIterator("../pages") as $page) {
          $pageName = $page->getFilename();
          $allowedPages[] = $pageName;
      }

      if (array_search($action, $allowedPages)) {
          require_once "../pages/" . $action;
      } else {
          require_once "../pages/error.php";
      }
      ?>
  </div>

    <?php require_once "../components/footer.html"; ?>

  <script>
      const allRanges = document.querySelectorAll(".range-label");

      allRanges.forEach(range => {
          const input = range.querySelector("input");
          const output = range.querySelector(".range-output");
          output.innerText = "Any";

          input.oninput = () => {
              output.innerText = input.value < 0
                  ? "Any"
                  : input.value;
          };
      });
  </script>
</body>
</html>