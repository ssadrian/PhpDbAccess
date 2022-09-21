<?php

$dbHostName = "localhost:3306";
$dbUser = "[Redacted]";
$dbPass = "[Redacted]";
$dbName = "school";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = mysqli_connect($dbHostName, $dbUser, $dbPass, $dbName);

if (mysqli_connect_errno()) {
    echo "Couldn't connect to MySQL database: " . mysqli_connect_error();
    die;
}

$db->query("
CREATE TABLE IF NOT EXISTS items (
    guid VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 0),
    aliases VARCHAR(255) NOT NULL,
    related_items VARCHAR(255) NOT NULL
)
");

function createPreparedStatement(string $qry): bool|mysqli_stmt
{
    global $db;
    return $db->prepare($qry);
}
