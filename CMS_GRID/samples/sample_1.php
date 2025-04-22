<?php
$gr = new grid;

$gr->id = 'tab_1';

$gr->info = [
    [      
        "type" => "options",	
        "caption" => "The sample data list",
        "maxheight" => "200px"
    ],
    [
        "type" => "table",
        "name" => "table_1",
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
                "name" => "name",
                "syn" => "n1",
                "type" => "string",
                "№" => 1,                
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
    ],
    [
        "type" => "button",
        "text" => "Обновить",
        "class" => "refresh"
    ]    
];

echo $gr->show();
?>

