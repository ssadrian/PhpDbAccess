<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="public/assets/libs/bootstrap-5.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/assets/libs/bootstrap-icons-1.9.1/bootstrap-icons.css">

    <title>Document</title>
</head>

<body>
    <?php
    require_once "components/header.html";

    $action = trim($_POST["action"] ?? "landpage");
    $allowedActions = ["landpage", "create", "read", "update", "delete", "error"];

    foreach (new DirectoryIterator("./pages") as $file) {
        $fileName = $file->getFilename();
        if (str_contains($fileName, $action)) {
            require_once $file->getRealPath();
            break;
        }
    }

    require_once "components/footer.html";
    ?>
</body>
</html>