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
//$form->addInput(["input" => ["type" => "select", "name" => "combobox", "selectType" => "kendoComboBox", "options" => ["satu", ["2", "dua", true], [3,"tiga"]]]]);
//$form->addInput(["label" => ["text" => "date"],"input" => ["type" => "date", "name" => "date"]]);
//$form->addInput(["label" => ["text" => "datetime"], "input" => ["type" => "datetime", "name" => "datetime"]]);
$form->addInput(["label" => ["text" => "rupiah"], "input" => ["type" => "number", "name" => "rupiah", "format" => "rupiah"]]);
$form->addInput(["label" => ["text" => "persen"], "input" => ["type" => "number", "name" => "persen", "format" => "percentage"]]);
$form->addInput(["label" => ["text" => "dec1"], "input" => ["type" => "number", "name" => "dec1", "format" => "dec1"]]);
$form->addInput(["label" => ["text" => "dec2"], "input" => ["type" => "number", "name" => "dec2", "format" => "dec2"]]);
$form->addInput(["label" => ["text" => "custom"], "input" => ["type" => "number", "name" => "custom", "format" => "#.00 kg"]]);
$form->render();
?>
