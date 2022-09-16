<?php

$firstname = $_POST['firstname'] ?? "";
$lastname = $_POST['lastname'] ?? "";
$age = $_POST['age'] ?? 0;

if ($firstname === "" && $lastname === "" && ($age === 0 || $age === "")) {
    echo "No data was provided";
    die;
}

echo "The full name is $firstname $lastname, age is $age";

$db = new mysqli("localhost:3306", "root", "", "test");

if ($qry = $db->prepare("INSERT INTO users (firstname, lastname, age) VALUES (?, ?, ?)")) {
    $qry->bind_param("ssi", $firstname, $lastname, $age);
    $qry->execute();
}

if ($result = $db->query("SELECT id, firstname, lastname, age FROM users")) {
    echo "<pre>";
    var_dump($result->fetch_all());
    echo "</pre>";
}

$db->close();
