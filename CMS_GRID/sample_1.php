<?php

session_start();

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title>Sample_1 - simple data list </title>

<link rel="stylesheet" type="text/css" href="grid.css" />

<script src="jquery-3.6.0.min.js"></script>
<script src="grid.js"></script>

</head>
<body>
';

include_once 'grid.php';

$gr = new grid;

$gr->id = 'id_1';

$gr->info = [
    [
        "type" => "table",
        "name" => "table_1",
        "syn" => "t1",
        "id_field_syn" => "id1",
        "fields" => [
            [
                "name" => "id",
                "syn" => "id1",
                "type" => "int",
                "visible" => "no"
            ],            
            [
                "name" => "name",
                "syn" => "n1",
                "type" => "string",
                "№" => 3,                
                "caption" => "Название"
            ]
        ]
    ],
    [
        "type"=>"button",
        "text"=>"Добавить",
        "class"=>"insert",
    ],
    [
        "type"=>"button",
        "text"=>"Сохранить",
        "class"=>"save"
    ],
    [
        "type"=>"button",
        "text"=>"Удалить",
        "class"=>"delete"
    ]
];

echo $gr->show();

?>

