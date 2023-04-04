<?php
namespace app\controllers;
use app\core\Controller;

class AuthController extends Controller
{
    public function __construct()
    {

    }

    public function login()
    {
        $this->setLayoutFileName("auth");
        $this->setPageTitle("Login Page");
        $this->setJsFile("login");
        //$this->setCssFile("admin");

        return $this->renderView("login");
    }
}
