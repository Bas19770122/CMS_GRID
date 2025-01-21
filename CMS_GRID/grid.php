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
}

class grid {

    public $id;
    public $ids;
    public $data;
    public $info;
    public $buttons;
    public $field_list; // full list field name
    public $field_visi; // visible list field name
    public $field_cap; // list header
    public $field_type; // field types
    public $js;
    public $inhtml;
    public $html;

    // actions 

    public function AddRec($js) {
        $newrec = [];
        $adddata = '';
        $data = json_decode($js, true);
        $field_visi = $_SESSION['fvisi_' . $this->id]; // visible fields synonimus
        $val = '';
        foreach ($field_visi as $j => $f) {
            
            
                if ($j == 0) {
                    $newrec[] = ['type' => 1];
                } 
                
                    $newrec[] = '';
                    $adddata = $adddata . '<div class=cell_class col=' . ($j) . ' row=' . count($data) . '>' . $val . '</div>';
                   
                
            
            
        }

        /*
          $data = json_decode($js, true);
          $adddata = '';
          foreach ($data as $i => $r) { // data from json
          $val = 'Новая строка';
          foreach ($r as $j => $c) {
          if ($j == 0) {
          $newrec[] = ['type' => 1];
          } else {
          $newrec[] = '';
          $adddata = $adddata.'<div class=cell_class col='.($j-1).' row='.count($data).'>'.$val.'</div>';
          $val = '';
          }
          }
          break;
          }
         */

        $data[] = $newrec;

        $js = [];
        $js['js'] = $data;
        $js['adddata'] = $adddata;

        return $this->Data_JS($js);
    }

    public function DelRec($js) {
        return $js;
    }

    public function ModRec($js) {
        global $server;
        global $user;
        global $password;
        global $schema;

        $sql = $this->JS_SQL($js);

        $mysqli = new mysqli($server, $user, $password, $schema);

        $mysqli->multi_query($sql); 
        
    //    $mysqli->commit();
      //  $mysqli->close();
        sleep(1);
       //

        $info = $_SESSION['info_' . $this->id];

        $this->refresh($info, null);

        // if all ok 
        /*
          $data = json_decode($js, true);
          foreach ($data as $i => $r) { // data from json
          if ($r[0]['type'] == 1) {
          $data[$i][0]['type'] = 0;
          }
          if ($r[0]['type'] == 2) {
          $data[$i][0]['type'] = 0;
          }
          }
         */

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
        $sql = '';
        $data = json_decode($js, true);
        foreach ($data as $i => $r) { // data from json 
            if ($r[0]['type'] == 1) { // data was inserted
                foreach ($ids as $t => $f) { // table circle      
                    $fld = '';
                    $fldv = '';
                    foreach ($field_list as $j => $fm) { // all field circle 
                        foreach ($field_visi as $jv => $fmv) { // visible field circle 
                            if ($fmv == $fm['syn'] && $t == $fm['tab']) {
                                if ($fld != '') {
                                    $fld .= ', ';
                                }
                                $fld .= $fm['name'];
                                if ($fldv != '') {
                                    $fldv .= ', ';
                                }
                                $fldv .= '"' . $r[$jv + 1] . '"';
                                break;
                            }
                        }
                    }
                    $sql .= 'insert into ' . $t . ' ( ' . $fld . ' ) values ( ' . $fldv . ' );';
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
                                $fld .= $fm['name'] . ' = "' . $r[$jv + 1] . '"';
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
        }
        //}
        return $sql;
    }

    public function Data_JS($data) {  // json from selected data
        $js = json_encode($data);
        return $js;
    }

    public function JS_Data($js) {
        return $data;
    }

    public function SQL_Data($sql, $field_visi, $field_list, $ids, $mysqli) { // get data from select SQL
        global $server;
        global $user;
        global $password;
        global $schema;
        if(!isset($mysqli))
          $mysqli = new mysqli($server, $user, $password, $schema);

        $_SESSION['ids_' . $this->id] = $ids; // array table - id_fields
        $_SESSION['fields_' . $this->id] = $field_list; // all fields
        $_SESSION['fvisi_' . $this->id] = $field_visi; // visible fields synonimus
        $_SESSION['data_' . $this->id] = []; // visible data  

        $data = [];
        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_assoc()) {
                $lst_fld = [];
                $lst_fld[] = ["type" => 0]; // select 
                foreach ($field_visi as $j => $f) {
                    $lst_fld[] = $row[$f];
                    /*
                      foreach ($field_list as $jf => $vf) {
                      if($vf['syn']==$f){
                      $lst_fld[] = $row[$vf['name']];
                      break;
                      }
                      }
                     */
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
        $ids = [];
        // $id_flds = [];
        //$all_flds = [];
        $_SESSION['info_' . $this->id] = $info;
        $sql = 'select <fields> from <tab> <where>';
        foreach ($info as $i => $v) {
            if ($v['type'] == 'table') {
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
        return [$sql, $flds/* $field_list */, $field_visi, $field_cap, $field_type, $buttons, $ids];
    }

    public function JS_Html($js, $field_list, $field_visi, $field_cap, $field_type, $buttons) { // get html code
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
            if (in_array($field_type[$j], ['date', 'number'])) {
                $element = 'input';
            }
            if (in_array($field_type[$j], ['string'])) {
                $element = 'textarea';
            }
            $fld[] = ['element' => $element, 'name' => $f, 'attr' => ['type' => $field_type[$j]]];
        }
        $html = $html . '<div id="json_f_' . $this->id . '" class="hidden">' . json_encode($fld) . '</div>';
        $style = '<style> #' . $this->id . '{display: grid; grid-auto-rows: auto 20px; grid-template-columns: ' . $style . ';} </style>';

        foreach ($arr as $i => $r) {
            $k = 0;
            foreach ($r as $j => $f) {
                if ($j > 0) {
                    foreach ($field_visi as $k => $fv) {
                        if ($j - 1 == $k) {
                            $html = $html . '<div class=cell_class col=' . $k . ' row=' . $i . '>' . $f . '</div>';
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
        $html = $html . '</div>';

        return [$inhtml, $html];
    }

    public function refresh($info, $mysqli) {

        list(
                $this->sql,
                $this->field_list,
                $this->field_visi,
                $this->field_cap,
                $this->field_type,
                $this->buttons,
                $this->ids
                ) = $this->Fields_SQL($info);

        $this->data = $this->SQL_Data($this->sql, $this->field_visi, $this->field_list, $this->ids, $mysqli);

        $this->js = $this->Data_JS($this->data);

        list($this->inhtml, $this->html) = $this->JS_Html($this->js, $this->field_list, $this->field_visi, $this->field_cap, $this->field_type, $this->buttons);
    }

    // show grid 

    public function show() {

        $this->refresh($this->info, null);

        return $this->html;
    }
}
?>



