<?php
namespace app\core;

class Form
{
    protected Application $app;
    protected string $id;
    protected string $html;
    protected string $jsGlobal;
    protected string $js;

    protected array $label;
    protected array $buttons;

    protected array $inputParams;

    public function __construct(array $params)
    {
        $this->app = Application::$app;

        $this->id = $params["id"];

        $this->buttons = $params["buttons"] ?? [];

        $this->label = $params["label"] ?? [];

        $this->ajax = $params["ajax"] ?? [];

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

    public function render()
    {
        $CSRF = new CSRF($_SESSION["arcee"]["key"]);
        $token = $CSRF->getToken($this->id);

        $this->html .= "<form id='{$this->id}'>";
            $this->html .= "<input type='hidden' name='formId' value='{$this->id}'/>";
            $this->html .= "<input type='hidden' name='token' value='{$token}'/>";
            $this->html .= "<input type='hidden' name='key' value='{$_SESSION["arcee"]["key"]}'/>";

        $this->jsDocumentReady .= "Arcee.Forms['{$this->id}'] = {};";
        $this->jsDocumentReady .= "Arcee.Forms['{$this->id}']['this'] = $('#{$this->id}');";
        $this->jsDocumentReady .= "Arcee.Forms['{$this->id}']['labels'] = {};";
        $this->jsDocumentReady .= "Arcee.Forms['{$this->id}']['inputs'] = {};";

        $this->generateInputs();

        $this->html .= "</form>";


        $this->generateButtons();
        $this->generateErrorMessage();
        $this->generateAjax();

        echo $this->html;
        echo "<script>$(document).ready(function () {".$this->jsDocumentReady."})</script>";
        if($this->jsGlobal)echo "<script>{$this->jsGlobal}</script>";
    }
        protected function generateInputs()
        {
            foreach($this->inputParams AS $params)
            {
                $label = $params["label"] ?? [];
                    $params["label"]["id"] = $label["id"] ?? $this->id.ucfirst($params["input"]["name"])."_label";
                    $params["label"]["text"] = $label["text"] ?? $params["input"]["name"];
                    $params["label"]["col"] = $label["col"] ?? 1;
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

                $this->html .= "<div class='row'>";
                    if(in_array($params["input"]["type"],["hidden"]))$this->generateInputHidden($params);
                    else if(in_array($params["input"]["type"],["text","email","password"]))$this->generateInputText($params);
                    else if(in_array($params["input"]["type"],["checkbox"]))$this->generateInputCheckbox($params);
                    else if(in_array($params["input"]["type"],["radio"]))$this->generateInputRadio($params);
                    else if(in_array($params["input"]["type"],["date"]))$this->generateInputDate($params);
                    else if(in_array($params["input"]["type"],["datetime"]))$this->generateDateTime($params);
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
            }
            protected function generateDateTime($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];
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
            }
            protected function generateInputSelect($params)
            {
                $this->generateLabel($params["label"]);
                $input = $params["input"];

                $selectType = $input["selectType"] ?? "kendoDropDownList";
                $options = $input["options"] ?? [];
                $selectedValue = $input["selectedValue"] ?? "";

                $this->html .= "<div class='col-{$input["col"]}'>";
                    $this->html .= "<input id='{$input["id"]}' name='{$input["name"]}'";
                        //if($input["value"])$this->html .= " value='{$input["value"]}'";
                        //if($input["width"])$this->html .= " style='width:{$input["width"]}'";
                    $this->html .= "/>";
                $this->html .= "</div>";

                $this->jsDocumentReady .= "$('#{$input["id"]}').{$selectType}({
                        dataTextField: 'text',
                        dataValueField: 'value',
                    ";
                    if(in_array($selectType,["kendoComboBox","kendoMultiSelect"]))$this->jsDocumentReady .= "placeholder: '{$input["placeholder"]}',";

                    if(count($options))
                    {
                        $this->jsDocumentReady .= "dataSource: [";
                            foreach($options AS $option)
                            {
                                if(is_array($option))
                                {
                                    $value = $option[0];
                                    $text = $option[1];
                                }
                                else
                                {
                                    $value = $option;
                                    $text = $option;
                                }
                                $this->jsDocumentReady .= "{ text: '{$text}', value: '{$value}' },";
                            }
                        $this->jsDocumentReady .= "],";
                    }
                $this->jsDocumentReady .= "});";
                $this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs['{$input["name"]}'] = $('#{$input["id"]}').data('{$selectType}');";
                if($selectedValue)$this->jsDocumentReady .= "Arcee.Forms.{$this->id}.inputs.{$input["name"]}.value('{$selectedValue}');";
            }

                protected function generateLabel($params)
                {
                    if($params["isShow"])
                    {
                        $this->html .= "<div class='col-{$params["col"]}'>";
                            $this->html .= "<label id='{$params["id"]}' for=''";
                            $this->html .= " class='col-{$params["col"]}'";
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

            $this->ajax["function"] = $submitOnClickFunction;

            $this->html .= "<div class='d-flex mt-4 pb-3 justify-content-{$justifyContent}'>";

                if($isShow && $cancelIsShow)$this->html .= "<button class='btn btn-{$cancelColor}' onClick='{$cancelOnClickFunction}();'>{$cancelText}</button>";
                if($isShow && $submitIsShow)$this->html .= "<button class='btn btn-{$submitColor}' onClick='{$submitOnClickFunction}();'>{$submitText}</button>";

            $this->html .= "</div>";
        }
        protected function generateErrorMessage()
        {
            $this->html .= "<div id='{$this->id}_errorMessage' class='d-flex pb-5 justify-content-center text-danger'></div>";
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
}
