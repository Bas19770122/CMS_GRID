<?php
$gr = new grid;

$gr->id = 'tab_5';

$gr->info = [
    [       
        "type" => "options",	
        "caption" => "The simple data table with a link to another tree table",        
        "maxheight" => "600px"
    ],
    [
        "type" => "table",
        "name" => "table_5_2",
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
                "name" => "caption",
                "syn" => "nm",
                "type" => "string",
                "№" => 1,
                "caption" => "Название"                
            ],
            [
                "name" => "table_5_1_id",
                "syn" => "p1",
                "type" => "list",
                "№" => 2,
                "caption" => "Родитель",
                "list" => "sample_5_tree.php",
                "namecaption" => "treeidname"
            ]			
        ]
    ],
    [
        "type" => "table",
        "name" => "table_5_1",
        "syn" => "t2",
        "join" => "left join",
        "on" => "t1.table_5_1_id = t2.id",
        "fields" => [
            [
                "name" => "name",
                "syn" => "treeidname",
                "visible" => "no",
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

echo $gr->show();   

?>


