<?php

/*

echo
'
<!DOCTYPE HTML>
<html>
<head>

<title>Sample_1 - simple data list </title>

<link rel="stylesheet" type="text/css" href="source/lib/grid.css" />

<script src="source/lib/jquery-3.6.0.min.js"></script>
<script src="source/lib/grid.js"></script>

</head>
<body>
';

include_once 'source/lib/grid.php';

*/

$gr = new grid;

$gr->id = 'tree_2';

$gr->info = [
    [
        "type" => "options",
        "root" => "null",
        "keysyn" => "id1", 
        "parentsyn" => "pid1"
    ], 
    [
        "type" => "table",
        "name" => "table_2",
        "syn" => "t1",
        "id_field_syn" => "id1",
        "editable" => "yes",        
        "fields" => [
            [
                "name" => "id",
                "syn" => "id1",
                "type" => "int",
                "visible" => "no"
            ],
			[
                "name" => "parentid",
                "syn" => "pid1",
                "type" => "int",
                "visible" => "no"
            ],            
            [
                "name" => "name",
                "syn" => "n1",
                "type" => "string",
                "№" => 1,                
                "caption" => "Название"
            ],            
            [
                "name" => "caption",
                "syn" => "c1",
                "type" => "string",
                "№" => 2,                
                "caption" => "Описание"
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
    ],
    [
        "type" => "button",
        "text" => "Обновить",
        "class" => "refresh"
    ]   
];

echo $gr->show();

?>

