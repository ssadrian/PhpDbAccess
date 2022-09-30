<?php

$dbHostName = "localhost:3306";
$dbUser = "root";
$dbPass = "";
$dbName = "school";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = mysqli_connect($dbHostName, $dbUser, $dbPass, $dbName);

if (mysqli_connect_errno()) {
    echo "Couldn't connect to MySQL database: " . mysqli_connect_error();
    die;
}

function createPreparedStatement(string $qry): bool|mysqli_stmt
{
    global $db;
    return $db->prepare($qry);
}
