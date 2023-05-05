<?php
namespace app\core;

class Ajax
{
    protected Application $app;

    protected array $get;
    protected array $post;

    protected int $isAuth;
    protected string $access;
    protected int $isAccess;
    public function __construct(array $params= [])
    {
        $this->app = Application::$app;

        $this->get = $params["get"] ?? [];
        $this->post = $params["post"] ?? [];
        $this->access = $params["access"] ?? "r";

        $this->isAuth = $this->post["isAuth"] ?? 0;

        $this->init();
    }

    //init
        protected function init()
        {
            if($this->isAuth)
            {
                $userId =  $this->post["userId"];
                $sessionKey =  $this->post["key"];

                $sp = new StoredProcedure();
                $rows = $sp->setName("sp_auth_checkSession")
                    ->setParameters(["i_userId" => $userId, "i_sessionKey" => $sessionKey])
                    ->prepare()
                    ->execute()
                    ->fetch();
                $statusCode = $rows[0]["statusCode"];

                if($statusCode == 100)
                {
                    $this->isAuth = 1;
                    $this->checkAccess();
                }
                else $this->isAuth = 0;
            }
        }
    //init


    //set variable
    //set variable

    //get / return variable
        public function isAuth()
        {
            return $this->isAuth;
        }
        public function isAccess()
        {
            return $this->isAccess;
        }
    //get / return variable

    //data proses
        protected function getPageId()
        {
            $scriptName = $this->app->getServerScriptName();
            $explode = explode("/",$scriptName);

            $folder = $explode[3];
            $pageId = intval(substr($folder,0,3));

            return $pageId;
        }
        protected function checkAccess()
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
            $statusCode = $rows[0]["statusCode"];

            if($statusCode == 100)$this->isAccess = 1;
            else $this->isAccess = 0;
        }
    //data proses
}
