<?php
namespace app\core;

class BSModal
{
    protected Application $app;
    protected string $id;
        protected string $elementId;
    protected string $html;
    protected string $jsGlobal;
    protected string $jsDocumentReady;

    protected string $title = "";
    protected string $body = "";
    protected string $footer = "";
    public function __construct(array $params)
    {
        $this->app = Application::$app;

        $this->id = $params["id"];
        $this->elementId = "bsmodal_{$this->id}";

        if(isset($params["title"]))$this->setTitle($params["title"]);
        if(isset($params["body"]))$this->setBody($params["body"]);
        if(isset($params["footer"]))$this->setFooter($params["footer"]);


        $this->init();
    }

    //init
        protected function init()
        {
            $this->html = "";
            $this->jsGlobal = "";
            $this->jsDocumentReady = "";

        }
    //init


    //set variable
        public function setTitle(string $title)
        {
            $this->title = $title;
        }
        public function setBody(string $body)
        {
            $this->body = $body;
        }
        public function setFooter(string $footer)
        {
            $this->footer = $footer;
        }
    //set variable

    //get / return variable
        public function render()
        {
            $this->generateBody();

            echo $this->html;
            if($this->jsGlobal)echo "<script>{$this->jsGlobal}</script>";
            echo "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
        }
        public function getBody()
        {
            $this->generateBody();

            $body = $this->html;
            if($this->jsGlobal)$body .= "<script>{$this->jsGlobal}</script>";
            $body .= "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
            return $body;
        }
    //get / return variable

    //data proses
        protected function generateBody()
        {
            $this->html .= "<div id='{$this->elementId}' class='modal fade' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h1 id='{$this->elementId}_title' class='modal-title fs-5' id='exampleModalLabel'>{$this->title}</h1>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div id='{$this->elementId}_body' class='modal-body'>{$this->body}</div>
                    <div id='{$this->elementId}_footer' class='modal-footer'>{$this->footer}</div>
                </div>
                </div>
            </div>";

            $this->jsGlobal .= "";
            $this->jsDocumentReady .= "Arcee.BSModals.{$this->id} = {};
                Arcee.BSModals.{$this->id}.this = new bootstrap.Modal(document.getElementById('{$this->elementId}'));
                Arcee.BSModals.{$this->id}.title = $('#{$this->elementId}_title');
                Arcee.BSModals.{$this->id}.body = $('#{$this->elementId}_body');
                Arcee.BSModals.{$this->id}.footer = $('#{$this->elementId}_footer');";

            $this->jsDocumentReady .= "Arcee.BSModals.{$this->id}.reset = function(){
                Arcee.BSModals.{$this->id}.title.html('{$this->title}');
                Arcee.BSModals.{$this->id}.body.html('{$this->body}');
                Arcee.BSModals.{$this->id}.footer.html('{$this->footer}');
            };";

            $this->jsDocumentReady .= "Arcee.BSModals.{$this->id}.open = function(params = {}){
                Arcee.BSModals.{$this->id}.reset();

                if('title' in params)Arcee.BSModals.{$this->id}.title.html(params.title);
                if('body' in params)Arcee.BSModals.{$this->id}.body.html(params.body);
                if('footer' in params)Arcee.BSModals.{$this->id}.footer.html(params.footer);

                let footer = Arcee.BSModals.{$this->id}.footer.html();

                if(footer === '') Arcee.BSModals.{$this->id}.footer.addClass('d-none');
                else Arcee.BSModals.{$this->id}.footer.removeClass('d-none');

                Arcee.BSModals.ajax.this.show();
            };";
        }
    //data proses
}
