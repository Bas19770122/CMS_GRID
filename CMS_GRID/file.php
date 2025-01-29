<?php

if (isset($_POST['grid_id'])) {

    echo '<div id=file_'.$_POST['grid_id'].' class=file_class>'
            . '<input type=file id=file_'.$_POST['grid_id'].'_'.$_POST['name'].' class=grid_file>'
            . '<button class=file_save>Сохранить</button>'
            . '<button class=file_cancel>Закрыть</button>'
            . '</div>';

}

?>

