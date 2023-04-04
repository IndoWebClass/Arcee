<?php
session_start();

/*
require_once __DIR__."/../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();
require_once __DIR__."/../../params.php";
*/

unset($_SESSION["arcee"]);

$result = true;
echo json_encode($result);
