<?php
namespace app\controllers;
use app\core\Controller;

class MasterController extends Controller
{
    public function __construct()
    {

    }

    public function master()
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

        return $this->renderView("01master", $vars);
    }

    public function employee()
    {
        return $this->renderView("006employee");
    }

    public function user()
    {
        return $this->renderView("001user");
    }

    public function product()
    {
        return $this->renderView("002product");
    }

    public function form()
    {
        return $this->renderView("999form");
    }
}
