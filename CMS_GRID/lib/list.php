<?php

 session_start();

if (isset($_POST['id'])) {

include_once 'grid.php';

$gr = new grid;

$gr->id = 'id_list_1';

$gr->info = [
    [
        "type" => "table",
        "name" => "table_1",
        "syn" => "t1",
        "id_field_syn" => "id1",
        "selected_val" => $_POST['id'],
        "show_id" => "yes",
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
        "type"=>"hidden",
        "id"=>"grid_list_id"
    ],   
    [
        "type"=>"hidden",
        "id"=>"grid_list_name"
    ],               
    [
        "type"=>"hidden",
        "id"=>"grid_list_action"
    ],       
    [
        "type"=>"button",
        "text"=>"Выбрать",
        "class"=>"select",
    ],
    [
        "type"=>"button",
        "text"=>"Отмена",
        "class"=>"cancel"
    ],
    [
        "type"=>"button",
        "text"=>"Очистить",
        "class"=>"clear"
    ],
    [
        "type"=>"page",
        "count"=>"5",
        "number"=>"1"
    ]
];

echo $gr->show();

}

?>

