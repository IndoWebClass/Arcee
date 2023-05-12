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

$ajax = new Ajax(["access" => "cr"]);

if($app->getStatusCode() == 100)
{
    $result["statusCode"] = $app->getStatusCode();
    $result["statusMessage"] = $app->getStatusMessage();
    $result["test"] = "ok";
}
else
{
    $result["statusCode"] = $app->getStatusCode();
    $result["statusMessage"] = $app->getStatusMessage();
}

echo json_encode($result);
