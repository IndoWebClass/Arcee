<h1>Employee</h1>

<?php
$params = [
    "id" => "getEmployee",
    "buttons" => [
        "cancelIsShow" => false,
        "submitText" => "CARI"
    ]
];

$form = new \app\core\Form($params);
$form->addInput(["label" => ["text" => "NIP"],"input" => ["name" => "id"]]);
$form->addInput(["label" => ["text" => "Nama"],"input" => ["name" => "name"]]);
$form->addInput(["label" => ["text" => "Jabatan"],"input" => ["name" => "positionName"]]);

$form->render();
?>

Form search employee


Kendo Grid list data karyawan


window untuk tambah data karyawan

window untuk edit data karyawan
