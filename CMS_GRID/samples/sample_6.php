<?php
$gr = new grid;

$gr->id = 'tab_6';


$gr->info = [
    [
        "type" => "options",
        "caption" => "The example of a table with different data types: checkbox, string, text, date, decimal, select, reference list, reference tree, file",
        "maxheight" => "500px"
    ],
    [
        "type" => "table",
        "name" => "table_6_1",
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
                "halign" => "center",
                "footer" => "count",
                "footertext" => "Количество"
            ],
            [
                "name" => "name",
                "syn" => "name",
                "type" => "string",
                "№" => 2,
                "caption" => "Название"
            ],
            [
                "name" => "discr",
                "syn" => "caption",
                "type" => "text",
                "№" => 3,
                "caption" => "Описание"
            ],      
            [
                "name" => "date",
                "syn" => "dt",
                "type" => "date",
                "№" => 4,
                "caption" => "Дата",
                "default" => '"2025-01-01"',
                "halign" => "center"
            ],
            [
                "name" => "sum",
                "syn" => "sm",
                "type" => "number",
                "№" => 5,
                "caption" => "Сумма",
                "halign" => "right",
                "footer" => "sum",
                "footertext" => "Сумма"
            ],
            [
                "name" => "selectid",
                "syn" => "sid",
                "type" => "select",
                "№" => 6,
                "caption" => "Тип",
                "options" => ["" => "пусто", "1" => "Тип 1", "2" => "Тип 2", "3" => "Тип 3", "4" => "Тип 4"]
            ],
            [
                "name" => "listid",
                "syn" => "lid",
                "type" => "list",
                "№" => 7,
                "caption" => "Справочник",
                "list" => "sample_6_list.php",
                "namecaption" => "listidname"
            ],
            [
                "name" => "treeid",
                "syn" => "prnt",
                "type" => "list",
                "№" => 8,
                "caption" => "Справочник дерево",
                "list" => "sample_6_tree.php",
                "namecaption" => "treeidname"
            ],
            [
                "name" => "fname",
                "syn" => "fn",
                "type" => "file",
                "file" => "source/lib/file.php",
                "№" => 9,
                "caption" => "Файл"
            ]                  
        ]
    ],
    [
        "type" => "table",
        "name" => "table_6_2",
        "syn" => "t2",
        "join" => "left join",
        "on" => "t1.listid = t2.id",
        "fields" => [
            [
                "name" => "caption",
                "syn" => "listidname",
                "visible" => "no",
            ]
        ]
    ],
    [
        "type" => "table",
        "name" => "table_6_3",
        "syn" => "t3",
        "join" => "left join",
        "on" => "t1.treeid = t3.id",
        "fields" => [
            [
                "name" => "caption",
                "syn" => "treeidname",
                "visible" => "no",
            ]
        ]
    ],
    [
        "type" => "where",
        "text" => "where 1 = 1"
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
    ],
    [
        "type" => "page",
        "count" => "10",
        "number" => "1",
        "limit" => "10"
    ],
    [
        "type" => "search",
        "fields" => ["t1.chk", "t1.name", "t1.discr", "t1.date", "t1.sum", "t1.selectid", "t2.caption", "t3.caption"]
    ]
];

echo $gr->show();   

?>


