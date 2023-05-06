<?php
require_once __DIR__."/../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();
require_once __DIR__."/../../params.php";

use app\core\Application;
use app\core\Ajax;
use app\core\StoredProcedure;

$app = new Application(PARAMS);

$result = [];

$ajax = new Ajax(["post"=>$_POST, "isAuth" => true, "access" => "cr"]);

if($ajax->isAccess())
{
    $result["test"] = "ok";
}

echo json_encode($result);
