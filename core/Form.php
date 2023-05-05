<?php
namespace app\core;

class Form
{
    protected Application $app;
    protected string $id;
        protected string $elementId;
    protected string $html;
    protected string $jsGlobal;
    protected string $jsDocumentReady;

    protected array $label;
    protected array $buttons;

    protected array $inputParams;

    public function __construct(array $params)
    {
        $this->app = Application::$app;

        $this->id = $params["id"];
        $this->elementId = "form_{$this->id}";

        $this->buttons = $params["buttons"] ?? [];

        $this->label = $params["label"] ?? [];

        $this->ajax = $params["ajax"] ?? [];

        $this->errorMessage = $params["errorMessage"] ?? [];

        $this->init();
    }

    protected function init()
    {
        $this->html = "";
        $this->jsGlobal = "";
        $this->jsDocumentReady = "";
    }


    public function addInput(array $params)
    {
        $this->inputParams[] = $params;
    }

    protected function generateBody()
    {
        $CSRF = new CSRF($_SESSION["arcee"]["key"]);
        $token = $CSRF->getToken($this->id);

        $this->html .= "<form id='{$this->elementId}'>";
            $this->html .= "<input type='hidden' name='formId' value='{$this->id}'/>";
            $this->html .= "<input type='hidden' name='token' value='{$token}'/>";
            $this->html .= "<input type='hidden' name='key' value='{$_SESSION["arcee"]["key"]}'/>";

        if(isset($_SESSION["arcee"]["isLogin"]) && $_SESSION["arcee"]["isLogin"])
        {
            $this->html .= "<input type='hidden' name='isAuth' value='1'/>";
            $this->html .= "<input type='hidden' name='userId' value='{$_SESSION["arcee"]["userId"]}'/>";
        }

        $this->jsGlobal .= "Arcee.Forms['{$this->id}'] = {};";
        $this->jsGlobal .= "Arcee.Forms['{$this->id}']['this'] = $('#{$this->elementId}');";
        $this->jsGlobal .= "Arcee.Forms['{$this->id}']['labels'] = {};";
        $this->jsGlobal .= "Arcee.Forms['{$this->id}']['inputs'] = {};";

        $this->generateInputs();

        $this->html .= "</form>";

        $this->generateButtons();
        $this->generateErrorMessage();
        $this->generateAjax();
    }
    public function render()
    {
        $this->generateBody();

        echo $this->html;
        if($this->jsGlobal)echo "<script>{$this->jsGlobal}</script>";
        echo "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
    }
        protected function generateInputs()
        {
            foreach($this->inputParams AS $params)
            {
                $label = $params["label"] ?? [];
                    $params["label"]["id"] = $label["id"] ?? $this->id.ucfirst($params["input"]["name"])."_label";
                    $params["label"]["text"] = $label["text"] ?? $params["input"]["name"];
                    $params["label"]["col"] = $label["col"] ?? $this->label["col"] ?? 1;
                    $params["label"]["width"] = $label["width"] ?? "";
                    $params["label"]["isShow"] = $label["isShow"] ?? $this->label["isShow"] ?? true;
                    if(!$params["label"]["isShow"]) $params["label"]["col"] = 0;

                $input = $params["input"];
                    //$params["input"]["name"] = $input["name"];
                    $params["input"]["id"] = $this->id.ucfirst($input["name"]);
                    $params["input"]["value"] = $input["value"] ?? "";
                    $params["input"]["placeholder"] = $input["placeholder"] ?? ucfirst($input["name"]);
                    $params["input"]["col"] = 12 - $params["label"]["col"];
                    $params["input"]["width"] = $input["width"] ?? "";
                    $params["input"]["type"] = $input["type"] ?? "text";

                    $params["label"]["inputName"] = $params["input"]["name"];

                $this->html .= "<div class='row align-items-center'>";
                    if(in_array($params["input"]["type"],["hidden"]))$this->generateInputHidden($params);
                    else if(in_array($params["input"]["type"],["text","email","password"]))$this->generateInputText($params);
                    else if(in_array($params["input"]["type"],["checkbox"]))$this->generateInputCheckbox($params);
                    else if(in_array($params["input"]["type"],["radio"]))$this->generateInputRadio($params);
                    else if(in_array($params["input"]["type"],["date"]))$this->generateInputDate($params);
                    else if(in_array($params["input"]["type"],["datetime"]))$this->generateInputDateTime($params);
                    else if(in_array($params["input"]["type"],["file"]))$this->generateInputFile($params);
                    else if(in_array($params["input"]["type"],["number"]))$this->generateInputNumber($params);
                    else if(in_array($params["input"]["type"],["select"]))$this->generateInputSelect($params);

                    //else echo $params["input"]["type"];
                $this->html .= "</div>";
            }
        }
            protected function generateInputHidden($params)
            {
                $input = $params["input"];

                $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}' type='{$input["type"]}'";
                    if($input["value"])$this->html .= " value='{$input["value"]}'";
                $this->html .= "/>";

                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}');";
            }
            protected function generateInputText($params)
            {
                $this->generateLabel($params["label"]);

                $input = $params["input"];

                $size = $input["size"] ?? "small";
                $rounded = $input["rounded"] ?? "medium";
                $fillMode = $input["fillMode"] ?? "solid";

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}' type='{$input["type"]}'";
                        if($input["value"])$this->html .= " value='{$input["value"]}'";
                        if($input["width"])$this->html .= " style='width:{$input["width"]}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').kendoTextBox({
                        placeholder: '{$input["placeholder"]}'
                    });";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoTextBox');";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'].setOptions({
                    size: '{$size}',
                    rounded: '{$rounded}',
                    fillMode: '{$fillMode}'
                });";
            }
            protected function generateInputCheckbox($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $label = $input["label"] ?? $input["name"];
                $checked = $input["checked"] ?? false;
                $enabled = $input["enabled"] ?? true;

                $size = $input["size"] ?? "small";
                $rounded = $input["rounded"] ?? "medium";

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}' type='{$input["type"]}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').kendoCheckBox({
                    label: '{$label}',
                    checked: ".($checked ? "true" : "false").",
                    enabled: ".($enabled ? "true" : "false")."
                });";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoCheckBox');";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'].setOptions({
                    size: '{$size}',
                    rounded: '{$rounded}',
                });";
            }
            protected function generateInputRadio($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $options = $input["options"];
                $checked = $input["checked"] ?? false;
                $enabled = $input["enabled"] ?? true;

                $size = $input["size"] ?? "small";

                $this->html .= "<div class='col-{$input["col"]}'>";
                    foreach($options as $option)
                    {
                        $input["id"] = $input["id"]."_".$option;

                        $this->html .= "<p><input id='{$input["id"]}' name='{$input["name"]}' type='{$input["type"]}' value='{$option}'";
                        $this->html .= "/></p>";

                        $this->jsDocumentReady .= "$('#{$input["id"]}').kendoRadioButton({
                            label: '{$option}',
                            checked: ".($checked ? "true" : "false").",
                            enabled: ".($enabled ? "true" : "false")."
                        });";
                        $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoRadioButton');";
                        $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'].setOptions({
                            size: '{$size}',
                        });";
                    }
                $this->html .= "</div>";
            }
            protected function generateInputDate($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $width = $input["width"] ? $input["width"] : "150px";
                $value = $input["value"] ? $input["value"] : date("Y-m-d");

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}' value='{$value}'";
                    $this->html .= " style='width:{$width}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').kendoDatePicker({
                        format: 'yyyy-MM-dd'";
                $this->jsDocumentReady .= "});";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoDatePicker');";
            }
            protected function generateInputDateTime($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $width = $input["width"] ? $input["width"] : "240px";
                $value = $input["value"] ? $input["value"] : date("Y-m-d H:i:s");

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}' value='{$value}'";
                    $this->html .= " style='width:{$width}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').kendoDateTimePicker({
                        format: 'yyyy-MM-dd HH:mm:ss'";
                $this->jsDocumentReady .= "});";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoDateTimePicker');";
            }
            protected function generateInputFile($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];
            }
            protected function generateInputNumber($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $width = $input["width"] ? $input["width"] : "240px";
                $format = $input["format"] ?? "";
                $decimals = $input["decimals"] ?? 0;
                $min = $input["min"] ?? 1;
                $max = $input["max"] ?? "x";
                $step = $input["step"] ?? 1;

                if($format == "rupiah")
                {
                    $width = "12.7rem";
                    $format = "Rp #,#.##";
                    $decimals = 2;
                }
                else if($format == "percentage")
                {
                    $width = "9.1rem";
                    $format = "#.## \'%\'";
                    $min = 0.01;
                    $max = 100;
                    $step = 0.01;
                    $decimals = 2;
                }
                else if($format == "dec1")
                {
                    $width = "9.1rem";
                    $format = "n1";
                    $min = 0.1;
                    $decimals = 1;
                    $step = 0.1;
                }
                else if($format == "dec2")
                {
                    $width = "9.1rem";
                    $format = "n2";
                    $min = 0.01;
                    $decimals = 2;
                    $step = 0.01;
                }

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}'";
                    $this->html .= " style='width:{$width}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').kendoNumericTextBox({";
                    if($format)$this->jsDocumentReady .= "format: '{$format}',";
                    if($decimals)$this->jsDocumentReady .= "decimals: {$decimals},";
                    if($min != "x")$this->jsDocumentReady .= "min: {$min},";
                    if($max != "x")$this->jsDocumentReady .= "max: {$max},";
                    $this->jsDocumentReady .= "step: {$step},";
                $this->jsDocumentReady .= "});";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('kendoNumericTextBox');";
            }
            protected function generateInputSelect($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $selectType = $input["selectType"] ?? "kendoDropDownList";
                $options = $input["options"] ?? [];

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}'";
                        //if($input["width"])$this->html .= " style='width:{$input["width"]}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').{$selectType}({
                        dataTextField: 'text',
                        dataValueField: 'value',
                    ";
                    if(in_array($selectType,["kendoComboBox","kendoMultiSelect"]))$this->jsDocumentReady .= "placeholder: '{$input["placeholder"]}',";
                $this->jsDocumentReady .= "});";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('{$selectType}');";

                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs.{$input["name"]}.reset = function(){
                    Arcee.Forms.{$this->id}.inputs.{$input["name"]}.setDataSource(new kendo.data.DataSource({data: []}));
                    Arcee.Forms.{$this->id}.inputs.{$input["name"]}.select(-1);
                    Arcee.Forms.{$this->id}.inputs.{$input["name"]}.value('');
                };";

                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs.{$input["name"]}.populate = function(datas){
                    Arcee.Forms.{$this->id}.inputs.{$input["name"]}.reset();

                    let options = [];
                    let selected = '';
                    for(let data of datas){
                        let value = '';
                        let text = '';

                        if(data instanceof Array){
                            value = data[0];
                            text = data[1];
                            if(data.length == 3 && data[2]){
                                selected = value;
                            }
                        }
                        else{
                            value = data;
                            text = data;
                        }
                        options.push({value:value, text:text});
                    }
                    Arcee.Forms.{$this->id}.inputs.{$input["name"]}.setDataSource(new kendo.data.DataSource({data: options}));
                    if(selected)Arcee.Forms.{$this->id}.inputs.{$input["name"]}.value(selected);
                };";
                if(count($options))
                {
                    $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs.{$input["name"]}.populate(".json_encode($options).");";
                }
            }

                protected function generateLabel($params)
                {
                    if($params["isShow"])
                    {
                        $this->html .= "<div class='col-{$params["col"]}'>";
                            $this->html .= "<label id='{$params["id"]}' for=''";
                            $this->html .= " class=''";
                            if($params["width"])$this->html .= " style='width:{$params["width"]}'";
                            $this->html .= ">{$params["text"]}</label>";
                        $this->html .= "</div>";
                        $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.labels['{$params["inputName"]}'] = $('#{$params["id"]}');";
                    }
                }

        protected function generateButtons()
        {
            $justifyContent = $this->buttons["justifyContent"] ?? "end";

            $isShow = $this->buttons["isShow"] ?? true;

            $cancelIsShow = $this->buttons["cancelIsShow"] ?? true;
            $cancelText = $this->buttons["cancelText"] ?? "CANCEL";
            $cancelColor = $this->buttons["cancelColor"] ?? "danger";
            $cancelOnClickFunction = $this->buttons["cancelOnClickFunction"] ?? "";

            $submitIsShow = $this->buttons["submitIsShow"] ?? true;
            $submitText = $this->buttons["submitText"] ?? "SUBMIT";
            $submitColor = $this->buttons["submitColor"] ?? "primary";
            $submitOnClickFunction = $this->buttons["submitOnClickFunction"] ?? $this->id."Submit";

            $additionals = $this->buttons["additionals"] ?? [];

            $this->ajax["function"] = $submitOnClickFunction;

            $this->html .= "<div class='d-flex mt-4 pb-3 justify-content-{$justifyContent}'>";

                if($isShow && $cancelIsShow)$this->html .= "<button class='m-1 btn btn-{$cancelColor}' onClick='{$cancelOnClickFunction}();'>{$cancelText}</button>";
                if($isShow && $submitIsShow)$this->html .= "<button class='m-1 btn btn-{$submitColor}' onClick='{$submitOnClickFunction}();'>{$submitText}</button>";
                if(count($additionals))
                {
                    foreach($additionals AS $additional)
                    {
                        $color = $additional["color"] ?? "primary";
                        $onClickFunctions = $additional["onClickFunctions"] ?? [];
                        $text = $additional["text"] ?? "ADD-ON";

                        $onClickFunction = "";
                        foreach($onClickFunctions AS $function)
                        {
                            $onClickFunction .= $function.";";
                        }
                        if($isShow)$this->html .= "<button class='m-1 btn btn-{$color}' onClick='{$onClickFunction}'>{$text}</button>";
                    }
                }
            $this->html .= "</div>";
        }
        protected function generateErrorMessage()
        {
            $params = $this->errorMessage;
            $isShow = $params["isShow"] ?? false;
            if($isShow)
            {
                $this->html .= "<div id='{$this->id}_errorMessage' class='d-flex pb-5 justify-content-center text-danger'></div>";
            }
        }
        protected function generateAjax()
        {
            $params = $this->ajax;

            $isRender = $params["isRender"] ?? true;

            if($isRender)
            {
                $function = $params["function"];
                $fileName = $params["fileName"] ?? $this->id;
                $url = $params["url"] ?? "/Arcee/ajax/{$this->app->getRouterContentFileName()}/{$fileName}.php";

                $this->jsGlobal .= "function {$function}(){
                    let params = {
                        url : '{$url}',
                        formId : '{$this->id}'
                    };
                    {$this->id}_ajax = new Ajax(params);
                    {$this->id}_ajax.render();
                };";
            }
        }

    public function getBody()
    {
        $this->generateBody();

        $body = $this->html;
        if($this->jsGlobal)$body .= "<script>{$this->jsGlobal}</script>";
        $body .= "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
        return $body;
    }
}
