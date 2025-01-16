<?php

$server = "localhost";
$user = "root";
$password = "root";
$schema = "new_schema";

class grid {

    public $id;
    public $data;
    public $fields;
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

    public function SQL_Data($sql, $field_list) { // get data from select SQL
        global $server;
        global $user;
        global $password;
        global $schema;
        $mysqli = new mysqli($server, $user, $password, $schema);

        $data = [];
        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_assoc()) {
                $lst_fld = [];
                $lst_fld[] = ["type" => 0]; // select 
                foreach ($field_list as $j => $f) {
                    $lst_fld[] = $row[$f];
                }

                $data[] = $lst_fld;
            }
        }

        return $data;
    }

    public function JS_SQL($js) { // insert, update, delete SQL
        return $sql;
    }

    public function Fields_SQL($fields) { // get select SQL
        $sql = '';
        $field_list = [];
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
                foreach ($v['fields'] as $j => $f) {
                    if (isset($f['№'])) {
                        $f['tsyn'] = $syn;
                        $flds[$f['№']] = $f;
                        if ($f['№'] < $minno) {
                            $minno = $f['№'];
                        }
                        if ($f['№'] > $maxno) {
                            $maxno = $f['№'];
                        }
                    }
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
        }
        for ($i = $minno; $i <= $maxno; $i++) {
            if (isset($flds[$i])) {
                $f = $flds[$i];
                $syn = $f['tsyn'];
                if ($fld != '') {
                    $fld = $fld . ', ';
                }
                $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];
                $field_list[] = $f['syn'];
                if (!isset($f['visible']) || ($f['visible'] == 'yes')) {
                    $field_visi[] = $f['syn'];
                    $field_type[] = $f['type'];
                    if (isset($f['caption'])) {
                        $field_cap[] = $f['caption'];
                    } else {
                        $field_cap[] = '';
                    }
                }
            }
        }

        $sql = str_replace('<fields>', $fld, $sql);
        $sql = str_replace('<tab>', $tab, $sql);
        $sql = str_replace('<where>', $whe, $sql);
        return [$sql, $field_list, $field_visi, $field_cap, $field_type];
    }

    public function JS_Html($js, $field_list, $field_visi, $field_cap, $field_type) { // get html code
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
            $fld[] = ['element'=>$element, 'name' => $f, 'attr'=>['type'=>$field_type[$j]]];
        }
        $html = $html . '<div id="json_f_' . $this->id . '" class="hidden">' . json_encode($fld) . '</div>';
        $style = '<style> #' . $this->id . '{display: grid; grid-auto-rows: auto 20px; grid-template-columns: ' . $style . ';} </style>';
        
        foreach ($arr as $i => $r) {
            $k = 0;
            foreach ($r as $j => $f) {
                if ($j > 0) {
                    foreach ($field_visi as $k => $fv) {
                        if ($fv == $field_list[$j - 1]) {
                            $html = $html . '<div class=cell_class col=' . $k . ' row=' . $i . '>' . $f . '</div>';
                            $k = $k + 1;
                            break;
                        }
                    }
                }
            }
        }
        $html = $style . $html . '</div>';
        return $html;
    }

    // show grid 

    public function show() {

        list($this->sql, $this->field_list, $this->field_visi, $this->field_cap, $this->field_type) = $this->Fields_SQL($this->fields);
        $this->data = $this->SQL_Data($this->sql, $this->field_list);
        $this->js = $this->Data_JS($this->data);
        $this->html = $this->JS_Html($this->js, $this->field_list, $this->field_visi, $this->field_cap, $this->field_type);

        return $this->html;
    }
}
?>



