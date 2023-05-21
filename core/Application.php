<?php
namespace app\core;

class Application
{
    protected array $params;
    protected Router $router;
    protected Server $server;
    public static Application $app;

    protected int $statusCode = 100;
    protected array $additionalStatusMessages;

    public function __construct(array $params)
    {
        self::$app = $this;
        $this->params = $params;
        $this->server = new Server();
        $this->router = new Router($this->server);

        $this->statusCode = 100;
        $this->init();
    }


    //init
        protected function init()
        {
            if($this->statusCode == 100)
            {

            }
        }
    //init

    //set variable
        public function setRoute(string $url, $callback)
        {
            if($this->statusCode == 100)
            {
                $this->router->setRoute($url, $callback);
            }
        }
        public function setStatusCode(int $statusCode, array $additionalStatusMessages = [])
        {
            if($this->statusCode == 100)
            {
                $this->statusCode = $statusCode;
                $this->additionalStatusMessages = $additionalStatusMessages;
            }
        }
    //set variable

    //get / return variable
        public function getParam(string $key)
        {
            if($this->statusCode == 100)
            {
                return $this->params[$key];
            }
        }
        public function getServerPath()
        {
            if($this->statusCode == 100)
            {
                return $this->server->getPath();
            }
        }
        public function getServerScriptName()
        {
            if($this->statusCode == 100)
            {
                return $this->server->getScriptName();
            }
        }
        public function getRouterContentFileName()
        {
            if($this->statusCode == 100)
            {
                return $this->router->getContentFileName();
            }
        }
        public function getStatusCode()
        {
            return $this->statusCode;
        }
        public function getStatusMessage()
        {
            $message = "Status Code not recognize";
            switch($this->statusCode)
            {
                case 100 : $message = "OK"; break;

                case 101 : $message = "SESSION ERROR : NO LOGIN RECORD"; break;
                case 102 : $message = "SESSION ERROR : SESSION EXPIRED"; break;

                case 201 : $message = "ACCESS ERROR : NO ACCESS"; break;
                case 202 : $message = "ACCESS ERROR : NO CREATE ACCESS"; break;
                case 203 : $message = "ACCESS ERROR : NO READ ACCESS"; break;
                case 204 : $message = "ACCESS ERROR : NO UPDATE ACCESS"; break;
                case 205 : $message = "ACCESS ERROR : NO DELETE ACCESS"; break;

                case 301 : $message = "AJAX ERROR : VALIDATION ERROR : {$this->additionalStatusMessages[0]} RULE {$this->additionalStatusMessages[1]}"; break;
            }

            return $message;
        }
    //get / return variable

    //data proses
        public function run()
        {
            if($this->statusCode == 100)
            {
                return $this->router->run();
            }
        }
        public function renderView(string $contentFileName, array $vars, bool $isPage)
        {
            if($this->statusCode == 100)
            {
                return $this->router->renderView($contentFileName, $vars, $isPage);
            }
        }
    //data proses











}
