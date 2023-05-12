<?php
namespace app\core;

class Ajax
{
    protected Application $app;

    protected array $get;
    protected array $post;

    protected bool $isAuth;
    protected string $access;

    public function __construct(array $params= [])
    {
        $this->app = Application::$app;

        $this->get = $_GET;
        $this->post = $_POST;
        $this->access = $params["access"] ?? "r";
        $this->isAuth = $params["isAuth"] ?? true;

        $this->init();
    }

    //init
        protected function init()
        {
            if($this->app->getStatusCode() == 100)
            {
                $this->checkCSRF();

                if($this->isAuth)
                    $this->checkSession();

                $this->checkAccess();
            }
        }
    //init


    //set variable
    //set variable

    //get / return variable
        public function isAuth()
        {
            if($this->app->getStatusCode() == 100)
            {
                return $this->isAuth;
            }
        }
        public function isAccess()
        {
            if($this->app->getStatusCode() == 100)
            {
                return $this->isAccess;
            }
        }
    //get / return variable

    //data proses
        protected function getPageId()
        {
            if($this->app->getStatusCode() == 100)
            {
                $scriptName = $this->app->getServerScriptName();
                $explode = explode("/",$scriptName);

                $folder = $explode[3];
                $pageId = intval(substr($folder,0,3));

                return $pageId;
            }
        }
        protected function checkCSRF()
        {
            if($this->app->getStatusCode() == 100)
            {
                $CSRF = new CSRF($this->post["key"]);
                if(!$CSRF->isTokenValid($this->post["formId"], $this->post["token"]))$this->isOk = false;
            }
        }
        protected function checkSession()
        {
            if($this->app->getStatusCode() == 100)
            {
                $userId =  $this->post["userId"];
                $sessionKey =  $this->post["key"];

                $sp = new StoredProcedure();
                $rows = $sp->setName("sp_auth_checkSession")
                    ->setParameters(["i_userId" => $userId, "i_sessionKey" => $sessionKey])
                    ->prepare()
                    ->execute()
                    ->fetch();
                $this->app->setStatusCode($rows[0]["statusCode"]);
            }
        }
        protected function checkAccess()
        {
            if($this->app->getStatusCode() == 100 && $this->isAuth)
            {
                $userId =  $this->post["userId"];
                $pageId = $this->getPageId();
                $c = str_contains($this->access, "c") ? 1 : 0;
                $r = str_contains($this->access, "r") ? 1 : 0;
                $u = str_contains($this->access, "u") ? 1 : 0;
                $d = str_contains($this->access, "d") ? 1 : 0;

                $sp = new StoredProcedure();
                $rows = $sp->setName("sp_ajax_checkAccess")
                    ->setParameters([
                        "i_userId" => $userId,
                        "i_pageId" => $pageId,
                        "i_c" => $c,
                        "i_r" => $r,
                        "i_u" => $u,
                        "i_d" => $d
                        ])
                    ->prepare()
                    ->execute()
                    ->fetch();
                $this->app->setStatusCode($rows[0]["statusCode"]);
            }
        }
    //data proses
}
