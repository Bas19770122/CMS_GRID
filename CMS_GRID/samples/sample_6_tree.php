<?php

 session_start();

if (isset($_POST['id'])) {

include_once 'source/lib/grid.php';

$gr = new grid;

$gr->id = 'tree_6';

$gr->info = [
    [
        "type" => "options",
        "root" => "null",
        "keysyn" => "id1", 
        "parentsyn" => "p1",
        "maxheight" => "200px"
    ],    
    [
        "type" => "table",
        "name" => "table_6_3",
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
                "name" => "caption",
                "syn" => "n1",
                "type" => "string",
                "№" => 1,                  
                "caption" => "Название"
            ],            
            [
                "name" => "table_6_3_id",
                "syn" => "p1",
                "type" => "int",
                "visible" => "no"               
            ]
        ]
    ],
    /*[
        "type" => "where",
        "text" => 'where t1.id <> "'.$_POST['id'].'"'
    ], */ 
    [
        "type"=>"hidden",
        "id"=>"grid_list_id",
        "value"=>$_POST['id']
    ],   
    [
        "type"=>"hidden",
        "id"=>"grid_list_name",
        "value"=>$_POST['text']
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
    ],
    [
        "type"=>"search",
        "fields"=>["t1.caption"]
    ]
];

echo $gr->show();

}

?>

