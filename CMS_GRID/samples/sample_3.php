<?php

/*
session_start();

echo
'
<!DOCTYPE HTML> 
<html>
<head>

<title></title>

<link rel="stylesheet" type="text/css" href="source/lib/grid.css" />

<script src="source/lib/jquery-3.6.0.min.js"></script>
<script src="source/lib/grid.js"></script>
<script src="source/list/list.js"></script>
<script src="source/tree/tree.js"></script>
<script src="source/lib/file.js"></script>


</head>
<body>
';

include_once 'source/lib/grid.php';
*/



$gr = new grid;

$gr->id = 'tab_3';

$gr->info = [
    [       
        "type" => "options",	
        "caption" => "A simple two data tables in one grid",
        "maxheight" => "200px"
    ],
    [
        "type" => "table",
        "name" => "table_3_1",
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
                "name" => "name",
                "syn" => "nm",
                "type" => "string",
                "№" => 1,
                "caption" => "Название"                
            ]
        ]
    ],
    [
        "type" => "table",
        "name" => "table_3_2",
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
                "name" => "caption",
                "syn" => "cp",
                "type" => "text",
                "№" => 2,
                "caption" => "Описание"
            ]
        ]
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
    ],
    [
        "type" => "button",
        "text" => "Обновить",
        "class" => "refresh"
    ]
];
/*
  [
        "type" => "order",
        "text" => "order by t2.id"
    ],    
 * 
 *  */


echo $gr->show();
?>


