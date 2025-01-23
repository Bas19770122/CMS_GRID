<?php

$server = "localhost";
$user = "root";
$password = "root";
$schema = "new_schema";

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        session_start();
        $gr = new grid;
        $gr->id = $_POST['id'];
        $data = $gr->ModRec($_POST['data']);
        echo $data;
        exit();
    }
    if ($_POST['action'] == 'insert') {
        session_start();
        $gr = new grid;
        $gr->id = $_POST['id'];
        $data = $gr->AddRec($_POST['data']);
        echo $data;
        exit();
    }
    /* if ($_POST['action'] == 'delete') {
      session_start();
      $gr = new grid;
      $gr->id = $_POST['id'];
      $data = $gr->DelRec($_POST['data']);
      echo $data;
      exit();
      } */
}

class grid {

    public $id;
    public $ids;
    public $data;
    public $info;
    public $buttons;
    public $hiddens;
    public $field_list; // full list field name
    public $field_visi; // visible list field name
    public $field_cap; // list header
    public $field_type; // field types
    public $js;
    public $inhtml;
    public $html;
    public $show_id; 

    // actions 

    public function AddRec($js) {
        $newrec = [];
        $adddata = '';
        $data = json_decode($js, true);
        $field_visi = $_SESSION['fvisi_' . $this->id]; // visible fields synonimus
        $field_list = $_SESSION['fields_' . $this->id]; // all fields
        $val = '';
        $k = 0;

        foreach ($field_visi as $jv => $fmv) { // visible field circle 
            if ($k == 0) {
                $newrec[] = ['type' => 1];
            }
            $k = $k + 1;
            foreach ($field_list as $j => $fm) { // all field circle 
                if ($fmv == $fm['syn']) {


                    //foreach ($field_visi as $j => $f) {


                    if ($fm['type'] == 'select' || $fm['type'] == 'list') {
                        $newrec[] = ['id' => '', 'text' => ''];
                    } else {
                        $newrec[] = '';
                    }


                    $adddata = $adddata . '<div class=cell_class col=' . ($jv) . ' row=' . count($data) . '>' . $val . '</div>';
                    break;
                }
            }
        }

        $data[] = $newrec;

        $js = [];
        $js['js'] = $data;
        $js['adddata'] = $adddata;

        return $this->Data_JS($js);
    }

    /*
      public function DelRec($js) {

      return $this->ModRec($js);
      } */

    public function ModRec($js) {
        global $server;
        global $user;
        global $password;
        global $schema;

        $sql = $this->JS_SQL($js);

        $mysqli = new mysqli($server, $user, $password, $schema);

        $mysqli->multi_query($sql);

        do {
            /* сохранить набор результатов в PHP */
            if ($result = $mysqli->store_result()) {
                while ($row = $result->fetch_row()) {
                    // printf("%s\n", $row[0]);
                }
            }
            /* вывести разделитель */
            if ($mysqli->more_results()) {
                //  printf("-----------------\n");
            }
        } while ($mysqli->next_result());

        $info = $_SESSION['info_' . $this->id];

        $this->refresh($info);

        $res = [];

        $res['js'] = $this->js;
        $res['html'] = $this->inhtml;

        return $this->Data_JS($res);
    }

    // transform 

    public function JS_SQL($js) { // insert, update, delete SQL
        //if ($tp == 2) { //modify 
        $ids = $_SESSION['ids_' . $this->id]; // array table - id_fields
        $field_list = $_SESSION['fields_' . $this->id]; // all fields
        $field_visi = $_SESSION['fvisi_' . $this->id]; // visible fields synonimus
        $all_data = $_SESSION['data_' . $this->id]; // all field data not only visible 
        $info = $_SESSION['info_' . $this->id];
        $sql = '';
        $data = json_decode($js, true);
        foreach ($data as $i => $r) { // data from json 
            if ($r[0]['type'] == 1) { // data was inserted
                //foreach ($ids as $t => $f) { // table circle      
                foreach ($info as $t => $f) { // table circle      
                    if ($f['type'] == 'table') {

                        $fld = '';
                        $fldv = '';
                        foreach ($field_list as $j => $fm) { // all field circle 
                            $isvis = 0;
                            foreach ($field_visi as $jv => $fmv) { // visible field circle 
                                if ($fmv == $fm['syn'] && $f['name'] == $fm['tab']) {
                                    $isvis = 1;
                                    if ($fld != '') {
                                        $fld .= ', ';
                                    }
                                    $fld .= $fm['name'];
                                    if ($fldv != '') {
                                        $fldv .= ', ';
                                    }
                                    if (isset($fm['default']) and ($r[$jv + 1] == '')) {
                                        $fldv .= $fm['default'];
                                    } else {
                                        if ($fm['type'] == 'select' || $fm['type'] == 'list') {
                                            if ($r[$jv + 1]['id'] == '') {
                                                $v = 'null';
                                            } else {
                                                $v = '"' . htmlspecialchars($r[$jv + 1]['id']) . '"';
                                            }
                                        } else {
                                            if (($fm['type'] == 'date' || $fm['type'] == 'number') && $r[$jv + 1] == '') {
                                                $v = 'null';
                                            } else {
                                                $v = '"' . htmlspecialchars($r[$jv + 1]) . '"';
                                            }
                                        }
                                        $fldv .= $v;
                                    }

                                    break;
                                }
                            }
                            if ($isvis == 0 && $f['name'] == $fm['tab']) {
                                if (isset($fm['default'])) {
                                    if ($fld != '') {
                                        $fld .= ', ';
                                    }
                                    $fld .= $fm['name'];
                                    if ($fldv != '') {
                                        $fldv .= ', ';
                                    }

                                    $fldv .= $fm['default'];
                                }
                            }
                        }
                        $after_insert = '';
                        if (isset($f['after_insert'])) {
                            $after_insert = $f['after_insert'];
                        }
                        $sql .= 'insert into ' . $f['name'] . ' ( ' . $fld . ' ) values ( ' . $fldv . ' );' . $after_insert;
                    }
                }
            }

            if ($r[0]['type'] == 2) { // data was modified 
                foreach ($ids as $t => $f) { // table circle      
                    $fld = '';
                    $fn = '';
                    foreach ($field_list as $j => $fm) { // all field circle 
                        foreach ($field_visi as $jv => $fmv) { // visible field circle 
                            if ($fmv == $fm['syn'] && $t == $fm['tab']) {
                                if ($fld != '') {
                                    $fld .= ', ';
                                }
                                if ($fm['type'] == 'select' || $fm['type'] == 'list') {
                                    if ($r[$jv + 1]['id'] == '') {
                                        $v = 'null';
                                    } else {
                                        $v = '"' . htmlspecialchars($r[$jv + 1]['id']) . '"';
                                    }
                                } else {
                                    if (($fm['type'] == 'date' || $fm['type'] == 'number') && $r[$jv + 1] == '') {
                                        $v = 'null';
                                    } else {

                                        $v = '"' . htmlspecialchars($r[$jv + 1]) . '"';
                                    }
                                }
                                $fld .= $fm['name'] . ' = ' . $v;
                                break;
                            }
                        }
                        if ($f == $fm['syn']) {
                            $fn = $fm['name'];
                        }
                    }
                    $fv = '';
                    foreach ($all_data as $ii => $dd) { // get id value
                        if ($ii == $i) {
                            foreach ($dd as $jj => $ff) {
                                if ($f == $jj) {
                                    $fv = $ff;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    $sql .= 'update ' . $t . ' set ' . $fld . ' where ' . $fn . ' = ' . $fv . ';';
                }
            }
            ///
            if ($r[0]['type'] == 3) { // data was deleted 
                foreach (array_reverse($ids) as $t => $f) { // table circle      
                    $fn = '';
                    foreach ($field_list as $j => $fm) { // all field circle 
                        if ($f == $fm['syn']) {
                            $fn = $fm['name'];
                            break;
                        }
                    }
                    $fv = '';
                    foreach ($all_data as $ii => $dd) { // get id value
                        if ($ii == $i) {
                            foreach ($dd as $jj => $ff) {
                                if ($f == $jj) {
                                    $fv = $ff;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    $sql .= 'delete from ' . $t . ' where ' . $fn . ' = ' . $fv . ';';
                }
            }
        }
        //}
        return $sql . ' COMMIT;';
    }

    public function Data_JS($data) {  // json from selected data
        $js = json_encode($data);
        return $js;
    }

    public function JS_Data($js) {
        return $data;
    }

    public function SQL_Data($sql, $field_visi, $field_list, $ids, $show_id) { // get data from select SQL
        global $server;
        global $user;
        global $password;
        global $schema;
        $mysqli = new mysqli($server, $user, $password, $schema);

        $_SESSION['ids_' . $this->id] = $ids; // array table - id_fields
        $_SESSION['fields_' . $this->id] = $field_list; // all fields
        $_SESSION['fvisi_' . $this->id] = $field_visi; // visible fields synonimus
        $_SESSION['data_' . $this->id] = []; // visible data  

        $data = [];
        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_assoc()) {
                $lst_fld = [];
                if ($show_id == 'yes') {
                    $id = '';
                    foreach ($ids as $t => $idv) {
                        $id = $idv; // first id
                        break;
                    }
                    $lst_fld[] = ["type" => 0, "id" => $row[$id]]; // select 
                } else {
                    $lst_fld[] = ["type" => 0]; // select 
                }
                foreach ($field_visi as $jv => $fv) {
                    foreach ($field_list as $j => $f) {
                        if ($f['syn'] == $fv) {
                            if ($f['type'] == 'select') {
                                if (isset($row[$fv])) {
                                    $lst_fld[] = ["id" => $row[$fv], "text" => htmlspecialchars($f['options'][$row[$fv]])];
                                } else {
                                    $lst_fld[] = ["id" => "", "text" => ""];
                                }
                            } else {
                                if ($f['type'] == 'list') {
                                    if (isset($row[$fv])) {
                                        $lst_fld[] = ["id" => $row[$fv], "text" => htmlspecialchars($row[$f['namecaption']])];
                                    } else {
                                        $lst_fld[] = ["id" => "", "text" => ""];
                                    }
                                } else {
                                    $lst_fld[] = htmlspecialchars($row[$fv]);
                                }
                            }
                            break;
                        }
                        /*
                          foreach ($field_list as $jf => $vf) {
                          if($vf['syn']==$f){
                          $lst_fld[] = $row[$vf['name']];
                          break;
                          }
                          }
                         */
                    }
                }


                $data[] = $lst_fld;

                $_SESSION['data_' . $this->id][] = $row; // all field data not only visible 
            }
        }



        return $data;
    }

    public function Fields_SQL($info) { // get select SQL
        $sql = '';
        //$field_list = [];
        $field_visi = [];
        $field_cap = [];
        $field_type = [];
        $fld = '';
        $tab = '';
        $whe = '';
        $joi = '';
        $on = '';
        $minno = 999999;
        $maxno = 0;
        $flds = [];
        $buttons = [];
        $hiddens = [];
        $ids = [];
        $show_id = '';
        // $id_flds = [];
        //$all_flds = [];
        $_SESSION['info_' . $this->id] = $info;
        $sql = 'select <fields> from <tab> <where>';
        foreach ($info as $i => $v) {
            if ($v['type'] == 'table') {
                if (isset($v['show_id']))
                    $show_id = $v['show_id'];
                if (isset($v['syn']))
                    $syn = $v['syn'];
                if (isset($v['join']))
                    $joi = $v['join'];
                if (isset($v['on']))
                    $on = $v['on'];
                if ($on != '') {
                    $on = ' on ' . $on;
                }
                if (isset($v['id_field_syn'])) {
                    $ids[$v['name']] = $v['id_field_syn'];
                }
                $tab = $tab . ' ' . $joi . ' ' . $v['name'] . ' as ' . $v['syn'] . $on;
                //$id_flds[] = [['tab'=>]
                foreach ($v['fields'] as $j => $f) {
                    $f['tsyn'] = $syn;
                    $f['tab'] = $v['name'];
                    if (isset($f['№'])) {
                        $flds[$f['№']] = $f;
                        if ($f['№'] < $minno) {
                            $minno = $f['№'];
                        }
                        if ($f['№'] > $maxno) {
                            $maxno = $f['№'];
                        }
                    } else {
                        $flds[$v['name'] . '_' . $f['name']] = $f;
                    }
                    //$all_flds[$v['name']]['fields'][] = [$f['name'],$f['syn']];
                    /*
                      if ($fld != '') {
                      $fld = $fld . ', ';
                      }
                      $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];
                      $field_list[] = $f['syn'];
                      if (!isset($f['visible']) || ($f['visible'] == 'yes')) {
                      $field_visi[] = $f['syn'];
                      }
                      if (isset($f['caption'])) {
                      $field_cap[] = $f['caption'];
                      }
                     */
                }
            }
            if ($v['type'] == 'where') {
                $whe = $v['text'];
            }
            if ($v['type'] == 'button') {
                $buttons[] = ['class' => $v['class'], 'text' => $v['text']];
            }
            if ($v['type'] == 'hidden') {
                $hiddens[] = ['id' => $v['id']];
            }
        }

        //$k = $minno;
        for ($i = $minno; $i <= $maxno; $i++) {
            //foreach ($flds as $j => $fv) {

            $f = $flds[$i];
            $syn = $f['tsyn'];
            if ($fld != '') {
                $fld = $fld . ', ';
            }
            $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];

            //if (isset($flds[$i])) {
            //if ($k == $i) {
            // if ($i == $j) {
            // $k = $k + 1;
            /*
              $f = $flds[$i];
              $syn = $f['tsyn'];
              if ($fld != '') {
              $fld = $fld . ', ';
              }
              $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];
             */
            // $field_list[] = $f['syn'];
            if (!isset($f['visible']) || ($f['visible'] == 'yes')) {
                $field_visi[] = $f['syn'];
                $field_type[] = $f['type'];
                if (isset($f['caption'])) {
                    $field_cap[] = $f['caption'];
                } else {
                    $field_cap[] = '';
                }
            }
            //}
            //}
            //}
        }

        foreach ($flds as $j => $f) {
            $isf = 0;
            for ($i = $minno; $i <= $maxno; $i++) {
                if ($i == $j) {
                    $isf = 1;
                }
            }
            if ($isf == 0) {
                $f = $flds[$j];
                $syn = $f['tsyn'];
                if ($fld != '') {
                    $fld = $fld . ', ';
                }
                $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];
            }
        }

        $sql = str_replace('<fields>', $fld, $sql);
        $sql = str_replace('<tab>', $tab, $sql);
        $sql = str_replace('<where>', $whe, $sql);
        return [$sql, $flds/* $field_list */, $field_visi, $field_cap, $field_type, $buttons, $hiddens, $ids, $show_id];
    }

    public function JS_Html($js, $field_list, $field_visi, $field_cap, $field_type, $buttons, $hiddens) { // get html code
        $arr = json_decode($js, true);
        $style = '';
        $temp_html = '<div class=grid_cont><div id=' . $this->id . ' class=grid_class><<html>></div>';
        $html = '';
        foreach ($field_cap as $j => $f) {
            $html = $html . '<div class=header_class>' . $f . '</div>';
            $style = $style . ' auto';
        }
        $html = $html . '<div id="json_' . $this->id . '" class="hidden">' . $js . '</div>';
        $fld = [];
        foreach ($field_visi as $j => $f) {
            if (in_array($field_type[$j], ['select'])) {
                $options = [];
                foreach ($field_list as $jf => $ff) {
                    if ($ff['syn'] == $f) {
                        $options = $ff['options'];
                        break;
                    }
                }
                $element = 'select';
                $fld[] = ['element' => $element, 'name' => $f, 'options' => $options, 'attr' => ['type' => $field_type[$j]]];
            } else {
                if (in_array($field_type[$j], ['list'])) {
                    $list = '';
                    foreach ($field_list as $jf => $ff) {
                        if ($ff['syn'] == $f) {
                            $list = $ff['list'];
                            break;
                        }
                    }
                    $element = 'list';
                    $fld[] = ['element' => $element, 'name' => $f, 'list' => $list, 'attr' => ['type' => $field_type[$j]]];
                } else {
                    if (in_array($field_type[$j], ['date', 'number'])) {
                        $element = 'input';
                    }
                    if (in_array($field_type[$j], ['select'])) {
                        $element = 'input';
                    }
                    if (in_array($field_type[$j], ['string'])) {
                        $element = 'textarea';
                    }
                    $fld[] = ['element' => $element, 'name' => $f, 'attr' => ['type' => $field_type[$j]]];
                }
            }
        }
        $html = $html . '<div id="json_f_' . $this->id . '" class="hidden">' . json_encode($fld) . '</div>';
        $style = '<style> #' . $this->id . '{display: grid; grid-auto-rows: auto 20px; grid-template-columns: ' . $style . ';} </style>';

        foreach ($arr as $i => $r) {
            $k = 0;
            foreach ($r as $j => $f) {
                if ($j > 0) {
                    foreach ($field_visi as $k => $fv) {
                        if ($j - 1 == $k) {
                            if (is_array($f)) {
                                $v = htmlspecialchars_decode($f['text']);
                            } else {
                                $v = htmlspecialchars_decode($f);
                            }
                            $html = $html . '<div class=cell_class col=' . $k . ' row=' . $i . '>' . $v . '</div>';
                            //$k = $k + 1;
                            break;
                        }
                    }
                }
            }
        }

        $inhtml = $style . $html;
        $html = str_replace('<<html>>', $inhtml, $temp_html);
        foreach ($buttons as $i => $v) {
            $html = $html . '<button class=' . $v['class'] . '>' . $v['text'] . '</button>';
        }

        foreach ($hiddens as $i => $v) {
            $html = $html . '<input id=' . $v['id'] . ' type=hidden></input>';
        }

        $html = $html . '</div>';

        return [$inhtml, $html];
    }

    public function refresh($info) {

        list(
                $this->sql,
                $this->field_list,
                $this->field_visi,
                $this->field_cap,
                $this->field_type,
                $this->buttons,
                $this->hiddens,
                $this->ids,
                $this->show_id
                ) = $this->Fields_SQL($info);

        $this->data = $this->SQL_Data($this->sql, $this->field_visi, $this->field_list, $this->ids, $this->show_id);

        $this->js = $this->Data_JS($this->data);

        list(
                $this->inhtml,
                $this->html
                ) = $this->JS_Html($this->js, $this->field_list, $this->field_visi, $this->field_cap, $this->field_type, $this->buttons, $this->hiddens);
    }

    // show grid 

    public function show() {

        $this->refresh($this->info);

        return $this->html;
    }
}
?>



