<?php
session_start();
//require_once("..\..\core\CSRF.php");
//require_once("..\..\core\StoredProcedure.php");

require_once __DIR__."/../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();
require_once __DIR__."/../../params.php";

use app\core\Application;
use app\core\CSRF;
use app\core\StoredProcedure;

$app = new Application(PARAMS);

$formId = $_POST["formId"];
$token = $_POST["token"];
$key = $_POST["key"];
$userName = $_POST["userName"];
$password = $_POST["password"];

$isLogin = 0;

$isValid = 0;
$CSRF = new CSRF($key);

//$token_new = hash_hmac("sha256", $formId." valid for ".date("Y-m-d"), $_SESSION["key"]);

$result["isError"] = false;
$result["errMessage"] = "";
$result["returnCode"] = 100;

$result["isLogin"] = 0;

if($CSRF->isTokenValid($formId, $token))
{
    $sp = new StoredProcedure();
    $rows = $sp->setName("sp_auth_login_checkUserName")
        ->setParameters(["userName" => $userName])
        ->prepare()
        ->execute()
        ->fetch();
    foreach($rows AS $row)
    {
        $passwordHash = $row["passwordHash"];
        if(password_verify($password, $passwordHash))
        {
            $userId = $row["id"];

            $sp->setName("sp_auth_login_saveSession")
                ->setParameters(["userId" => $userId])
                ->setParameters(["sessionKey" => $key])
                ->prepare()
                ->execute();

            $isLogin = 1;
            $_SESSION["arcee"]["isLogin"] = $isLogin;
            $_SESSION["arcee"]["userId"] = $userId;
            $result["isLogin"] = $isLogin;
        }
    }

    $query = $sp->getQuery();
    //$result["query"] = $query;
}

if(!$isLogin)
{
    $result["isError"] = true;
    $result["errMessage"] = "token is invalid";
    $result["returnCode"] = 101;
}

echo json_encode($result);
?>
