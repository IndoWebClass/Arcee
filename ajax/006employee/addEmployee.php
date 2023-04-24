<?php
require_once __DIR__."/../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();
require_once __DIR__."/../../params.php";

use app\core\Application;
use app\core\CSRF;
use app\core\StoredProcedure;

$app = new Application(PARAMS);

$result = [];

$formId = $_POST["formId"];
$token = $_POST["token"];
$key = $_POST["key"];
$CSRF = new CSRF($key);

if($CSRF->isTokenValid($formId, $token))
{
    unset($_POST["formId"]);
    unset($_POST["token"]);
    unset($_POST["key"]);

    $result = $_POST;
    $result["scriptName"] = $_SERVER['SCRIPT_NAME'];
}

echo json_encode($result);
