<h1>Employee</h1>

<?php
$params = [
    "id" => "getEmployee",
    "buttons" => [
        "cancelIsShow" => false,
        "submitText" => "CARI",
        "additionals" => [
            ["onClickFunctions" => ["Arcee.KendoWindows.addEmployee.center().open()"], "text" => "ADD EMPLOYEE"]
        ]
    ]
];

$form = new \app\core\Form($params);
$form->addInput(["label" => ["text" => "NIP"],"input" => ["name" => "id"]]);
$form->addInput(["label" => ["text" => "Nama"],"input" => ["name" => "name"]]);
$form->addInput(["label" => ["text" => "Jabatan"],"input" => ["name" => "positionName"]]);

$form->render();

/*
Kendo Grid list data karyawan


window untuk tambah data karyawan

window untuk edit data karyawan
*/
$params = [
    "id" => "addEmployee",
    "label" => ["col" => 4],
    "buttons" => [
        "submitText" => "TAMBAH",
        "cancelOnClickFunction" => "Arcee.KendoWindows.addEmployee.close"
    ]
];

$form = new \app\core\Form($params);
$form->addInput(["label" => ["text" => "Nama"],"input" => ["name" => "name"]]);
$form->addInput(["label" => ["text" => "Tanggal Lahir"],"input" => ["name" => "birthDate", "type" => "date"]]);
$form->addInput(["label" => ["text" => "Tempat Lahir"],"input" => ["name" => "birthPlace"]]);
$form->addInput(["label" => ["text" => "No KTP"],"input" => ["name" => "KTPNumber"]]);
$form->addInput(["label" => ["text" => "No NPWP"],"input" => ["name" => "NPWPNumber"]]);
$form->addInput(["label" => ["text" => "Tanggal Join"],"input" => ["name" => "enrolmentDate", "type" => "date"]]);
$form->addInput(["label" => ["text" => "Jabatan"],"input" => ["name" => "positionId"]]);
$form->addInput(["label" => ["text" => "Date Time"],"input" => ["name" => "datetime"]]);

//$form->render();

$params = [
    "id" => "addEmployee",
];
$window = new \app\core\KendoWindow($params);
$window->setBody($form->getBody());
$window->render();
?>
<style>
    .k-widget *{box-sizing: border-box;}
    .k-widget.k-window{z-index:1050 !important;}
</style>
