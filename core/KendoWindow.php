<?php
namespace app\core;

class KendoWindow
{
    protected Application $app;
    protected string $id;
    protected string $html;
    protected string $jsGlobal;
    protected string $jsDocumentReady;

    public function __construct(array $params)
    {
        $this->app = Application::$app;

        $this->id = "kendoWindow_{$params["id"]}";

        if(isset($params["body"]))$this->setBody($params["body"]);

        $this->init();
    }

    protected function init()
    {
        $this->html = "";
        $this->jsGlobal = "";
        $this->jsDocumentReady = "";
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    protected function generateBody()
    {
        $this->html .= "<div id='{$this->id}'>";
        $this->html .= $this->body;
        $this->html .= "</div>";

        //$this->jsGlobal .= "Arcee.KendoWindows['{$this->id}'] = $('#{$this->id}').kendoWindow();";

        $this->jsDocumentReady .= "
            let windowOptions = {
                actions: ['Minimize', 'Maximize', 'Close'],
                //draggable: true,
                //resizable: true,
                width: '500px',
                title: 'ADD EMPLOYEE',
                visible: false,
                //close: onClose
            };
            Arcee.KendoWindows['{$this->id}'] = $('#{$this->id}').kendoWindow(windowOptions).data('kendoWindow');";
    }
    public function render()
    {
        $this->generateBody();
        echo $this->html;
        if($this->jsGlobal)echo "<script>{$this->jsGlobal}</script>";
        echo "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
    }
}
