<?php
namespace app\core;

class Router
{
    protected array $routes;
    protected Server $server;
    protected Controller $controller;

    protected string  $contentFileName;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function setRoute(string $url, $callback)
    {
        $this->routes[$url] = $callback;
        //echo "Router : {$url} is set to call function: {$callback}<br/>";
    }
    public function getRoutes()
    {
        return $this->routes;
    }
    public function run()
    {
        $path = $this->server->getPath();

        $callback = $this->routes[$path] ?? false;

        if($callback === false)
        {
            return "404 Page not found";
        }
        if(is_string($callback))
        {
            return $this->renderView($callback);
        }
        if(is_array($callback))
        {
            //$controllerClass = $callback[0];
            //$controllerMethod = $callback[1];

            $this->controller = new $callback[0]();
            $callback[0] = $this->controller;

            return call_user_func($callback);
        }

        //return $callback;
    }
    public function renderView(string $contentFileName, array $vars = [], bool $isPage)
    {
        $layoutFileName = "default";
        if(isset($this->controller))
        {
            $layoutFileName = $this->controller->getLayoutFileName();
        }
        $layoutString = $this->renderLayout($layoutFileName);
        $contentString = $this->renderContent($contentFileName, $vars,$isPage);

        return str_replace("{{content}}", $contentString, $layoutString);
    }
        protected function renderLayout(string $layoutFileName)
        {
            ob_start();
            include_once __DIR__."/../views/layout/{$layoutFileName}.php";
            $layout = ob_get_clean();

            $pageTitle = "Arcee";
            $cssLink = "";
            $jsLink = "";
            if(isset($this->controller))
            {
                $pageTitle = $this->controller->getPageTitle();
                $cssLink = $this->generateCssLink();
                $jsLink = $this->generateJsLink();
            }

            $layout = str_replace("{{page_title}}", $pageTitle, $layout);
            $layout = str_replace("{{dynamic_css}}", $cssLink, $layout);
            $layout = str_replace("{{dynamic_js}}", $jsLink, $layout);

            return $layout;
        }
            protected function generateCssLink()
            {
                $cssFiles = $this->controller->getCssFiles();
                $link = "";

                foreach($cssFiles AS $cssFile)
                {
                    $link .= "<link href='/Arcee/src/css/{$cssFile}.css' rel='stylesheet'>";
                }

                return $link;
            }
            protected function generateJsLink()
            {
                $jsFiles = $this->controller->getJsFiles();
                $script = "";

                foreach($jsFiles AS $jsFile)
                {
                    $script .= "<script src='/Arcee/src/js/{$jsFile}.js'></script>";
                }

                return $script;
            }
        protected function renderContent(string $contentFileName, array $vars, bool $isPage)
        {
            $this->contentFileName = $contentFileName;
            foreach($this->server->getPathVars() AS $key => $value)
            {
                $$key = $value;
            }
            foreach($vars AS $key => $value)
            {
                $$key = $value;
            }
            ob_start();
            if($isPage)include_once __DIR__."/../views/content/page/{$contentFileName}.php";
            else include_once __DIR__."/../views/content/module/{$contentFileName}.php";
            return ob_get_clean();
        }
    public function getContentFileName()
    {
        return $this->contentFileName;
    }
}

?>
