<?php
namespace app\core;

class Ajax
{
    protected Application $app;
    public function __construct(array $params= [])
    {
        $this->app = Application::$app;


        $this->init();
    }

    //init
        protected function init()
        {

        }
    //init


    //set variable
    //set variable

    //get / return variable
        public function getPageId()
        {
            $scriptName = $this->app->getServerScriptName();
            $explode = explode("/",$scriptName);

            $folder = $explode[3];
            $pageId = intval(substr($folder,0,3));

            return $pageId;
        }
    //get / return variable

    //data proses
    //data proses
}
