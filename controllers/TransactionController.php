<?php
namespace app\controllers;
use app\core\Controller;

class TransactionController extends Controller
{
    public function __construct()
    {

    }

    public function trx()
    {
        $this->setLayoutFileName("default");
        $this->setPageTitle("Halaman Admin");
        $this->setJsFile("admin");
        $this->setJsFile("admin_additional");
        $this->setCssFile("admin");
        $this->setIsPage(false);

        $vars = [
            "welcomeStc" => "Welcome to Admin page",
        ];

        return $this->renderView("02trx", $vars);
    }

    public function po()
    {
        return $this->renderView("003po");
    }

    public function stock()
    {
        return $this->renderView("004stock");
    }

    public function sales()
    {
        return $this->renderView("005sales");
    }
}
