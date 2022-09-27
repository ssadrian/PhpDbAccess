<?php

require_once "utils/HtmlPurifier/HTMLPurifier.auto.php";

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
