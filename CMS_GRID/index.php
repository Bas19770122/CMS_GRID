<?php

session_start();

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title></title>

<link rel="stylesheet" type="text/css" href="grid.css" />

<script src="jquery-3.6.0.min.js"></script>
<script src="grid.js"></script>
<script src="list.js"></script>

</head>
<body>
';

include_once 'grid.php';

$gr = new grid;

$gr->id = 'id_1';

$gr->info = [
    [
        "type" => "table",
        "name" => "table1",
        "syn" => "t1",
        "id_field_syn" => "id1",
        "after_insert" => "set @id := LAST_INSERT_ID();",
        "editable" => "yes",
        "fields" => [
            [
                "name" => "id",
                "syn" => "id1",
                "type" => "int",
                "visible" => "no"
            ],
            [
                "name" => "chk",
                "syn" => "ch",
                "type" => "checkbox",
                "№" => 1,
                "caption" => "Признак",
                "checkedval" => "Y",
                "uncheckedval" => "N",
                "halign" => "center"
            ],            
            [
                "name" => "date",
                "syn" => "dt",
                "type" => "date",
                "№" => 4,
                "caption" => "Дата",
                "default" => '"2025-01-01"'
            ],
            [
                "name" => "sum",
                "syn" => "sm",
                "type" => "number",
                "№" => 5,
                "caption" => "Сумма"
            ],
            [
                "name" => "selectid",
                "syn" => "sid",
                "type" => "select",
                "№" => 6,
                "caption" => "Тип",
                "options" => ["1" => "Тип 1", "2" => "Тип 2", "3" => "Тип 3", "4" => "Тип 4"]
            ],
            [
                "name" => "listid",
                "syn" => "lid",
                "type" => "list",
                "№" => 7,
                "caption" => "Справочник",
                "list" => "list.php",
                "namecaption" => "listidname"
            ]
        ]
    ],
    [
        "type" => "table",
        "name" => "table_1",
        "syn" => "t3",
        "join" => "left join",
        "on" => "t1.listid = t3.id",
        "fields" => [
            [
                "name" => "name",
                "syn" => "listidname",
                "visible" => "no",
            ]
        ]
    ],
    [
        "type" => "table",
        "name" => "table2",
        "syn" => "t2",
        "id_field_syn" => "id2",
        "join" => "left join",
        "on" => "t1.id = t2.id",
        "editable" => "yes",
        "fields" => [
            [
                "name" => "id",
                "syn" => "id2",
                "type" => "int",
                "visible" => "no",
                "default" => "@id"
            ],
            [
                "name" => "name",
                "syn" => "name",
                "type" => "string",
                "№" => 2,
                "caption" => "Название"
            ],
            [
                "name" => "caption",
                "syn" => "caption",
                "type" => "string",
                "№" => 3,
                "caption" => "Описание"
            ]
        ]
    ],
    [
        "type" => "where",
        "text" => "where t2.id >= 1"
    ],
    [
        "type" => "button",
        "text" => "Добавить",
        "class" => "insert",
    ],
    [
        "type" => "button",
        "text" => "Сохранить",
        "class" => "save"
    ],
    [
        "type" => "button",
        "text" => "Удалить",
        "class" => "delete"
    ]
];

echo $gr->show();
?>

