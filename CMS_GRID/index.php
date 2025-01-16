<?php

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title></title>

<link rel="stylesheet" type="text/css" href="grid.css" />

<script src="jquery-3.6.0.min.js"></script>
<script src="grid.js"></script>

</head>
<body>
';

include_once 'grid.php';

$gr = new grid;

$gr->id = 'id_1';

$gr->fields = [
    [
        "type" => "table",
        "name" => "table1",
        "syn" => "t1",
        "id_field" => "id",
        "fields" => [
            [
                "name" => "id",
                "syn" => "id1",
                "type" => "int",
                "visible" => "no"
            ],            
            [
                "name" => "date",
                "syn" => "dt",
                "type" => "date",
                "№" => 3,                
                "caption" => "Дата"
            ],
            [
                "name" => "sum",
                "syn" => "sm",
                "type" => "number",
                "№" => 4,
                "caption" => "Сумма"
            ]
        ]
    ],    
    [
        "type" => "table",
        "name" => "table2",
        "syn" => "t2",
        "id_field" => "id",
        "join" => "left join",
        "on" => "t1.listid = t2.id",
        "fields" => [
            [
                "name" => "id",
                "syn" => "id2",
                "type" => "int",
                "visible" => "no"
            ],
            [
                "name" => "name",
                "syn" => "name",
                "type" => "string",
                "№" => 1,
                "caption" => "Название"
            ],
            [
                "name" => "caption",
                "syn" => "caption",
                "type" => "string",
                "№" => 2,
                "caption" => "Описание"
            ]
        ]
    ],
    [
        "type" => "where",
        "text" => "where t2.id between 1 and 3"
    ],
    [
        "type"=>"button",
        "text"=>"Сохранить",
        "class"=>"save"
    ]
];

echo $gr->show();

?>

