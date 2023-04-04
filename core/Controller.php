<?php
namespace app\core;

class Controller
{
    protected string $layoutFileName;
    protected string $pageTitle;
    protected array $jsFileNames;
    protected array $cssFileNames;

    protected bool $isPage = true;

    public function __construct()
    {

    }

    protected function setIsPage(bool $isPage)
    {
        $this->isPage = $isPage;
    }

    protected function renderView(string $contentFileName, array $vars = [])
    {
        return Application::$app->renderView($contentFileName, $vars, $this->isPage);
    }

    protected function setLayoutFileName(string $layoutFileName)
    {
        $this->layoutFileName = $layoutFileName;
    }
    protected function setPageTitle(string $pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }
    protected function setJsFile(string $sourceFileName)
    {
        $this->jsFileNames[] = $sourceFileName;
        //require __DIR__."../src/js/{$sourceFileName}.js";
    }
    protected function setCssFile(string $sourceFileName)
    {
        $this->cssFileNames[] = $sourceFileName;
        //require __DIR__."../src/css/{$sourceFileName}.css";
    }

    public function getLayoutFileName()
    {
        return $this->layoutFileName ?? "default";
    }
    public function getPageTitle()
    {
        return $this->pageTitle ?? "Arcee";
    }
    public function getJsFiles()
    {
        return $this->jsFileNames ?? [];
    }
    public function getCssFiles()
    {
        return $this->cssFileNames ?? [];
    }
}
