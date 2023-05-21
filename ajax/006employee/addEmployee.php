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
    $ajax->prepareValidation("post");
    $ajax->addValidation("name",["required","string",["max",100]]);
    $ajax->addValidation("birthDate",["required","date"]);
    $ajax->addValidation("KTPNumber",["numeric"]);
    $ajax->addValidation("positionId",["int"]);
    $ajax->validate();
}

if($app->getStatusCode() == 100)
{
    $result["ajaxIsOK"] = true;
}

$result["statusCode"] = $app->getStatusCode();
$result["statusMessage"] = $app->getStatusMessage();

echo json_encode($result);
