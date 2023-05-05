<?php
session_start();

if(!isset($_SESSION["arcee"]))
    $_SESSION["arcee"] = [];

$_SESSION["arcee"]["key"] = $_SESSION["arcee"]["key"] ?? bin2hex(random_bytes(32));

//var_dump($_SESSION);

require_once __DIR__."/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
require_once __DIR__."/../params.php";

use app\core\Application;

$app = new Application(PARAMS);

//$app->setRoute("/", "home");
//echo $app->getServerPath();
if(!isset($_SESSION["arcee"]["isLogin"]) || !$_SESSION["arcee"]["isLogin"])
{
    $app->setRoute($app->getServerPath(), [\app\controllers\AuthController::class, "login"]);
}
else
{
    $app->setRoute("/", [\app\controllers\DefaultController::class, "home"]);

    $app->setRoute("/master", [\app\controllers\MasterController::class, "master"]);
    $app->setRoute("/master/employee", [\app\controllers\MasterController::class, "employee"]);
    $app->setRoute("/master/user", [\app\controllers\MasterController::class, "user"]);
    $app->setRoute("/master/product", [\app\controllers\MasterController::class, "product"]);

    $app->setRoute("/master/form", [\app\controllers\MasterController::class, "form"]);

    $app->setRoute("/trx", [\app\controllers\TransactionController::class, "trx"]);
    $app->setRoute("/trx/po", [\app\controllers\TransactionController::class, "po"]);
    $app->setRoute("/trx/stock", [\app\controllers\TransactionController::class, "stock"]);
    $app->setRoute("/trx/sales", [\app\controllers\TransactionController::class, "sales"]);
}

$callback = $app->run();

echo $callback;
?>
