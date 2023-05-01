<?php
namespace app\core;

class Application
{
    protected array $params;
    protected Router $router;
    protected Server $server;
    public static Application $app;

    public function __construct(array $params)
    {
        self::$app = $this;
        $this->params = $params;
        $this->server = new Server();
        $this->router = new Router($this->server);
    }

    public function setRoute(string $url, $callback)
    {
        $this->router->setRoute($url, $callback);
    }

    public function getParam(string $key)
    {
        return $this->params[$key];
    }

    public function run()
    {
        return $this->router->run();
    }

    public function renderView(string $contentFileName, array $vars, bool $isPage)
    {
        return $this->router->renderView($contentFileName, $vars, $isPage);
    }

    public function getServerPath()
    {
        return $this->server->getPath();
    }
    public function getServerScriptName()
    {
        return $this->server->getScriptName();
    }
    public function getRouterContentFileName()
    {
        return $this->router->getContentFileName();
    }
}
