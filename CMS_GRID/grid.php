<?php


$server = "localhost";
$user = "root";
$password = "root";
$schema = "new_schema";

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'save') {
        $gr = new grid;
    }
}

class grid {

    public $id;
    public $data;
    public $fields;
    public $buttons;
    public $field_list; // full list field name
    public $field_visi; // visible list field name
    public $field_cap; // list header
    public $field_type; // field types
    public $js;
    public $html;

    // actions 

    public function AddRec($r, $c, $js) {
        return $js;
    }

    public function DelRec($r, $c, $js) {
        return $js;
    }

    public function ModRec($r, $c, $js) {
        return $js;
    }

    // transform 

    public function Data_JS($data) {  // json from selected data
        $js = json_encode($data);
        return $js;
    }

    public function JS_Data($js) {
        return $data;
    }

    public function SQL_Data($sql, $field_visi, $field_list) { // get data from select SQL
        global $server;
        global $user;
        global $password;
        global $schema;
        $mysqli = new mysqli($server, $user, $password, $schema);

        $_SESSION['fields_' . $this->id] = $field_list;
        $_SESSION['fvisi_' . $this->id] = $field_visi;
        $_SESSION['data_' . $this->id] = [];
        $data = [];
        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_assoc()) {
                $lst_fld = [];
                $lst_fld[] = ["type" => 0]; // select 
                foreach ($field_visi as $j => $f) {
                    $lst_fld[] = $row[$f];
                }

                $data[] = $lst_fld;

                $_SESSION['data_' . $this->id][] = $row;
            }
        }



        return $data;
    }

    public function JS_SQL($js) { // insert, update, delete SQL
        return $sql;
    }

    public function Fields_SQL($fields) { // get select SQL
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
        // $id_flds = [];
        //$all_flds = [];
        $sql = 'select <fields> from <tab> <where>';
        foreach ($fields as $i => $v) {
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
                $tab = $tab . ' ' . $joi . ' ' . $v['name'] . ' as ' . $v['syn'] . $on;
                //$id_flds[] = [['tab'=>]
                foreach ($v['fields'] as $j => $f) {
                    $f['tsyn'] = $syn;
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
        return [$sql, $flds/* $field_list */, $field_visi, $field_cap, $field_type, $buttons];
    }

    public function JS_Html($js, $field_list, $field_visi, $field_cap, $field_type, $buttons) { // get html code
        $arr = json_decode($js, true);
        $style = '';
        $html = '<div id=' . $this->id . ' class=grid_class>';
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
        foreach ($buttons as $i => $v) {
            $html = $html . '<button class=' . $v['class'] . '>' . $v['text'] . '</button>';
        }

        $html = $style . $html . '</div>';
        return $html;
    }

    // show grid 

    public function show() {

        list(
                $this->sql,
                $this->field_list,
                $this->field_visi,
                $this->field_cap,
                $this->field_type,
                $this->buttons) = $this->Fields_SQL($this->fields);
        $this->data = $this->SQL_Data($this->sql, $this->field_visi, $this->field_list);
        $this->js = $this->Data_JS($this->data);
        $this->html = $this->JS_Html($this->js, $this->field_list, $this->field_visi, $this->field_cap, $this->field_type, $this->buttons);

        return $this->html;
    }
}
?>



