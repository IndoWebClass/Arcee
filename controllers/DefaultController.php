<?php
namespace app\controllers;
use app\core\Controller;

class DefaultController extends Controller
{
    public function __construct()
    {

    }

    public function home()
    {
        $this->setLayoutFileName("default");
        $this->setPageTitle("Welcome to Arcee");
        //$this->setJsFile("default");
        //$this->setJsFile("admin_additional");
        //$this->setCssFile("admin");

        $vars = [

        ];

        return $this->renderView("home", $vars);
    }
}
