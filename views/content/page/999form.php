<h1>Test Form</h1>

<?php
$params = [
    "id" => "test",
    "buttons" => [
        "cancelIsShow" => false,
        "submitText" => "CARI"
    ],
];

$form = new \app\core\Form($params);
//$form->addInput(["input" => ["type" => "hidden", "name" => "hidden", "value" => 10]]);
//$form->addInput(["label" => ["text" => "text"],"input" => ["name" => "text"]]);
//$form->addInput(["label" => ["text" => "checkbox"],"input" => ["type" => "checkbox", "name" => "checkbox"]]);
//$form->addInput(["label" => ["text" => "radio"],"input" => ["type" => "radio", "name" => "radio", "options" => ["satu", "dua", "tiga"]]]);
//$form->addInput(["label" => ["text" => "radio2"],"input" => ["type" => "radio", "name" => "radio2", "options" => ["empat", "lima", "enam"]]]);
$form->addInput(["input" => ["type" => "select", "name" => "combobox", "selectType" => "kendoComboBox", "options" => ["satu", ["2", "dua"], [3,"tiga"]], "selectedValue" => "2"]]);
$form->render();
?>
