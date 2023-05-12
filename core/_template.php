<?php
namespace app\core;

class ClassName
{
    protected Application $app;
    public function __construct(array $params)
    {
        $this->app = Application::$app;


        $this->init();
    }

    //init
        protected function init()
        {
            if($this->app->getStatusCode() == 100)
            {

            }
        }
    //init


    //set variable
    //set variable

    //get / return variable
    //get / return variable

    //data proses
    //data proses
}
