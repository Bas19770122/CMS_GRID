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

        /*
          if (isset($_POST['number'])) {
          $number = $_POST['number'];
          } else {
          $number = '';
          }
         */
        list($number, $search, $searchfld, $sort, $sorttp) = getparams();

        $gr->searchval = $search;
        $gr->searchfld = $searchfld;

        $data = $gr->ModRec($_POST['data'], $number, $search, $searchfld, $sort, $sorttp);
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
    if ($_POST['action'] == 'refresh') {
        session_start();
        $gr = new grid;
        $gr->id = $_POST['id'];

        /*
          if (isset($_POST['number'])) {
          $number = $_POST['number'];
          } else {
          $number = null;
          }
          if (isset($_POST['search'])) {
          $search = json_decode($_POST['search'], true);
          // $_SESSION['srch_val_' . $this->id] = $search;
          } else {
          $search = null;
          }
          $gr->searchval = $search;
          if (isset($_POST['searchfld'])) {
          $searchfld = json_decode($_POST['searchfld'], true);
          // $_SESSION['srch_fld_' . $this->id] = $searchfld;
          } else {
          $searchfld = null;
          }
          $gr->searchfld = $searchfld;
         */

        list($number, $search, $searchfld, $sort, $sorttp) = getparams();

        $gr->searchval = $search;
        $gr->searchfld = $searchfld;

        $data = $gr->Ref($_POST['data'], $number, $search, $searchfld, $sort, $sorttp);
        echo $data;
        exit();
    }
}

function getparams() {

    if (isset($_POST['number'])) {
        $number = $_POST['number'];
    } else {
        $number = null;
    }
    if (isset($_POST['search'])) {
        $search = json_decode($_POST['search'], true);
        // $_SESSION['srch_val_' . $this->id] = $search;
    } else {
        $search = null;
    }

    if (isset($_POST['searchfld'])) {
        $searchfld = json_decode($_POST['searchfld'], true);
        // $_SESSION['srch_fld_' . $this->id] = $searchfld;
    } else {
        $searchfld = null;
    }

    if (isset($_POST['sortfld'])) {
        $sort = $_POST['sortfld'];
        // $_SESSION['srch_fld_' . $this->id] = $searchfld;
    } else {
        $sort = null;
    }

    if (isset($_POST['sorttp'])) {
        $sorttp = $_POST['sorttp'];
        // $_SESSION['srch_fld_' . $this->id] = $searchfld;
    } else {
        $sorttp = null;
    }

    return [$number, $search, $searchfld, $sort, $sorttp];
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
    public $inpager;
    public $html;
    public $show_id;
    public $selected_val;
    public $pagesql;
    public $sql;
    public $cnt;
    public $page;
    public $pnumber;
    public $search;
    public $searchval;
    public $searchfld;
    public $sqlf;
    public $footer;
    public $tree;
    public $root;
    public $key;
    public $parent;
    public $levels;
    public $pnum;

    // actions 

    public function AddRec($js) {
        $newrec = [];
        $adddata = '';
        $data = json_decode($js, true);
        $field_visi = $_SESSION['fvisi_' . $this->id]; // visible fields synonimus
        $field_list = $_SESSION['fields_' . $this->id]; // all fields       
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
                    $v = '';
                    $class = '';
                    if (in_array($field_list[$j]['type'], ['checkbox'])) {
                        // foreach ($field_list as $jf => $ff) {
                        // if ($fm['syn'] == $fv) {
                        if ($v == $fm['checkedval']) {
                            $v = '&check;';
                        }
                        if ($v == $fm['uncheckedval'] || $v == '') {
                            $v = '&#9744;';
                        }

                        //  break;
                        // }
                        //  }
                    }
                    if (isset($fm['halign'])) {
                        if ($fm['halign'] == 'center') {
                            $class = $class . ' halign_center';
                        }
                        if ($fm['halign'] == 'left') {
                            $class = $class . ' halign_left';
                        }
                        if ($fm['halign'] == 'right') {
                            $class = $class . ' halign_right';
                        }
                    }


                    $adddata = $adddata . '<div class="cell_class ' . $class . '" col=' . ($jv) . ' row=' . count($data) . '>' . $v . '</div>';
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

    public function getinfo($info, $number, $search, $searchfld, $sort, $sorttp) {

        // where 

        foreach ($info as $i => $v) {
            if (isset($searchfld)) {
                if ($v['type'] == 'where') {
                    if (isset($info[$i]['oldtext'])) {
                        $info[$i]['text'] = $info[$i]['oldtext'];
                        $info[$i]['oldtext'] = null;
                    }
                }
                if ($v['type'] == 'order') {
                    if (isset($info[$i]['oldtext'])) {
                        $info[$i]['text'] = $info[$i]['oldtext'];
                        $info[$i]['oldtext'] = null;
                        $info[$i]['ord'] = $info[$i]['oldord'];
                        $info[$i]['oldord'] = null;
                        $info[$i]['fld'] = $info[$i]['oldfld'];
                        $info[$i]['oldfld'] = null;
                    }
                }
            }
        }

        $issearch = 0;
        $issort = 0;
        foreach ($info as $i => $v) {
            if (isset($v['type'])) {
                if ($v['type'] == 'page') {
                    $info[$i]['number'] = $number;
                }
                if (isset($searchfld)) {
                    if ($v['type'] == 'where') {
                        if (!isset($info[$i]['oldtext'])) {
                            $info[$i]['oldtext'] = $info[$i]['text'];
                        }
                        foreach ($searchfld as $k => $f) {
                            if ($search[$k] != '') {
                                $info[$i]['text'] = $info[$i]['text'] . ' and ' . $f . " like '%" . $search[$k] . "%'";
                            }
                        }
                        $issearch = 1;
                    }
                    if ($v['type'] == 'order') {
                        if (!isset($info[$i]['oldtext'])) {
                            $info[$i]['oldtext'] = $info[$i]['text'];
                            $info[$i]['oldord'] = $info[$i]['ord'];
                            $info[$i]['oldfld'] = $info[$i]['fld'];
                        }
                        if ($sort != '') {
                            //$info[$i]['text'] = $info[$i]['text'] . ' ,  ' . $sort . ' ' . $sorttp;
                            if ($sorttp == '') {
                                $info[$i]['text'] = '';
                            } else {
                                $info[$i]['text'] = ' order by ' . $sort . ' ' . $sorttp;
                            }
                            $info[$i]['ord'] = $sorttp;
                            $info[$i]['fld'] = $sort;
                        } else {
                            $info[$i]['text'] = '';
                            $info[$i]['ord'] = '';
                            $info[$i]['fld'] = '';
                        }
                        $issort = 1;
                    }
                }
            }
        }
        if (isset($searchfld)) {
            if ($issearch == 0) {
                foreach ($searchfld as $k => $f) {
                    if ($search[$k] != '') {
                        $info[] = ['type' => 'where', 'text' => 'where ' . $f . " like '%" . $search[$k] . "%'"];
                    }
                }
            }
            if ($issort == 0) {
                if ($sort != '') {
                    $info[] = ['type' => 'order', 'text' => 'order by ' . $sort . ' ' . $sorttp, 'ord' => $sorttp, 'fld' => $sort];
                }
            }
        }




        return $info;
    }

    public function ModRec($js, $number, $search, $searchfld, $sort, $sorttp) {
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
        /*
          foreach ($info as $i => $v) {
          if (isset($v['type'])) {
          if ($v['type'] == 'page') {
          $info[$i]['number'] = $number;
          }
          }
          } */

        $info = $this->getinfo($info, $number, $search, $searchfld, $sort, $sorttp);

        $this->refresh($info, $search);

        $res = [];

        $res['js'] = $this->js;
        $res['html'] = $this->inhtml;
        $res['pager'] = $this->inpager;

        return $this->Data_JS($res);
    }

    public function Ref($js, $number, $search, $searchfld, $sort, $sorttp) {
        global $server;
        global $user;
        global $password;
        global $schema;

        $info = $_SESSION['info_' . $this->id];

        $info = $this->getinfo($info, $number, $search, $searchfld, $sort, $sorttp);

        $this->refresh($info, $search);

        $res = [];

        $res['js'] = $this->js;
        $res['html'] = $this->inhtml;
        $res['pager'] = $this->inpager;

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
                        $editable = 'no';
                        if (isset($f['editable'])) {
                            $editable = $f['editable'];
                        }
                        if ($editable == 'yes') {
                            foreach ($field_list as $j => $fm) { // all field circle 
                                $isvis = 0;
                                foreach ($field_visi as $jv => $fmv) { // visible field circle 
                                    if ($fmv == $fm['syn'] && $f['name'] == $fm['tab']) {
                                        if ($fm['type'] == 'file') {
                                            if (substr(trim($r[$jv + 1]), 0, 1) == '<') {
                                                continue;
                                            }
                                        }
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
                                                    $v = '"' . htmlspecialchars(addslashes($r[$jv + 1]['id'])) . '"';
                                                }
                                            } else {
                                                if (($fm['type'] == 'date' || $fm['type'] == 'number') && $r[$jv + 1] == '') {
                                                    $v = 'null';
                                                } else {
                                                    $v = '"' . htmlspecialchars(addslashes($r[$jv + 1])) . '"';
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
            }

            if ($r[0]['type'] == 2) { // data was modified 
                foreach ($info as $t => $f) { // table circle     
                    if ($f['type'] == 'table') {
                        $fld = '';
                        $fn = '';
                        $editable = 'no';
                        if (isset($f['editable'])) {
                            $editable = $f['editable'];
                        }
                        if ($editable == 'yes') {
                            foreach ($field_list as $j => $fm) { // all field circle 
                                foreach ($field_visi as $jv => $fmv) { // visible field circle 
                                    if ($fmv == $fm['syn'] && $f['name'] == $fm['tab']) {
                                        if ($fm['type'] == 'file') {
                                            if (substr(trim($r[$jv + 1]), 0, 1) == '<') {
                                                continue;
                                            }
                                        }
                                        if ($fld != '') {
                                            $fld .= ', ';
                                        }
                                        if ($fm['type'] == 'select' || $fm['type'] == 'list') {
                                            if ($r[$jv + 1]['id'] == '') {
                                                $v = 'null';
                                            } else {
                                                $v = '"' . htmlspecialchars(addslashes($r[$jv + 1]['id'])) . '"';
                                            }
                                        } else {
                                            if (($fm['type'] == 'date' || $fm['type'] == 'number') && $r[$jv + 1] == '') {
                                                $v = 'null';
                                            } else {

                                                $v = '"' . htmlspecialchars(addslashes($r[$jv + 1])) . '"';
                                            }
                                        }
                                        $fld .= $fm['name'] . ' = ' . $v;
                                        break;
                                    }
                                }
                                if ($f['id_field_syn'] == $fm['syn']) {
                                    $fn = $fm['name'];
                                }
                            }
                            $fv = '';
                            foreach ($all_data as $ii => $dd) { // get id value
                                if ($ii == $i) {
                                    foreach ($dd as $jj => $ff) {
                                        if ($f['id_field_syn'] == $jj) {
                                            $fv = $ff;
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                            $sql .= 'update ' . $f['name'] . ' set ' . $fld . ' where ' . $fn . ' = ' . $fv . ';';
                        }
                    }
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
        return $sql;
    }

    public function Data_JS($data) {  // json from selected data
        $js = json_encode($data);
        return $js;
    }

    public function JS_Data($js) {
        return $data;
    }

    public function loadrecord($result, $data, $field_visi, $field_list, $row_id, $row_no, $show_id, $selected_val, $ids, $levels, $lv, $i, $mysqli, $sql,
            $root, $key, $parent, $pnum, $p) {
        $lv = $lv + 1;

        //$sqlt = str_replace('<parent>', $parent, $sql);
        // if ($result = $mysqli->query($sqlt)) {

        $nrec = 0;

        $result->data_seek($nrec);

        while ($row = $result->fetch_assoc()) {
            $nrec = $nrec + 1;
            $fnd = false;
            if ($this->tree == 1) {
                $fnd = ($root == $row[$parent]);
            } else {
                $fnd = true;
            }
            if ($fnd) {
                $lst_fld = [];
                if ($show_id == 'yes') {
                    $id = '';
                    foreach ($ids as $t => $idv) {
                        $id = $idv; // first id
                        break;
                    }
                    $lst_fld[] = ["type" => 0, "id" => $row[$id]]; // select 
                    $row_id = $row[$id];
                } else {
                    $lst_fld[] = ["type" => 0]; // select 
                }
                foreach ($field_visi as $jv => $fv) {
                    foreach ($field_list as $j => $f) {
                        if ($f['syn'] == $fv) {
                            if ($selected_val != '' && $row_id == $selected_val) {
                                $row_no = $i;
                            }
                            if ($f['type'] == 'select') {
                                if (isset($row[$fv])) {
                                    $lst_fld[] = ["id" => $row[$fv], "text" => htmlspecialchars_decode($f['options'][$row[$fv]])];
                                    //$lst_fld[] = ["id" => $row[$fv], "text" => $f['options'][$row[$fv]]];
                                } else {
                                    $lst_fld[] = ["id" => "", "text" => ""];
                                }
                            } else {
                                if ($f['type'] == 'list') {
                                    if (isset($row[$fv])) {
                                        $lst_fld[] = ["id" => $row[$fv], "text" => htmlspecialchars_decode($row[$f['namecaption']])];
                                        //$lst_fld[] = ["id" => $row[$fv], "text" => $row[$f['namecaption']]];
                                    } else {
                                        $lst_fld[] = ["id" => "", "text" => ""];
                                    }
                                } else {
                                    /* if ($f['type'] == 'file') {
                                      if ($row[$fv] != '') {
                                      $lst_fld[] = '<img src="' . $row[$fv] . '" width=40 height=40>';
                                      } else {
                                      $lst_fld[] = '';
                                      }
                                      } else { */
                                    $lst_fld[] = htmlspecialchars_decode($row[$fv]);
                                    //}
                                    //$lst_fld[] = $row[$fv];
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
                $levels[] = $lv;
                $pnum[] = $p;
                $pi = $i;

                $_SESSION['data_' . $this->id][] = $row; // all field data not only visible 
                $i = $i + 1;

                // $inparent = '';
                if ($this->tree == 1) {
                    // $inparent = ' and ' . $this->parent . ' = "' . $row[$this->key] . '"';

                    $inroot = $row[$key];

                    list($result, $data, $levels, $i, $row_no, $pnum) = $this->loadrecord(
                            $result, $data, $field_visi, $field_list, $row_id, $row_no, $show_id, $selected_val, $ids, $levels, $lv, $i, $mysqli, $sql,
                            $inroot, $key, $parent, $pnum, $pi);
                }
                /* */
            }
            $result->data_seek($nrec);
        }
        //  $result->data_seek(0);
        // }

        return [$result, $data, $levels, $i, $row_no, $pnum];
    }

    public function SQL_Data($sql, $pagesql, $field_visi, $field_list, $ids, $show_id, $selected_val, $cnt) { // get data from select SQL
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
        $row_id = '';
        $row_no = -1;
        $i = 0;
        $levels = [];
        $lv = 0;
        $pnum = [];
        $p = -1;

        /*

          $tree;
          $root;
          $key;
          $parent;
         */
        /*
          $parent = '';

          if ($this->tree == 1) {
          if ($this->root == 'null') {
          $root = ' is null ';
          } else {
          $root = ' = "' . $this->root . '"';
          }
          $parent = ' and ' . $this->parent . $root;
          }
         */

        //$sqlt = str_replace('<parent>', $parent, $sql);

        if ($result = $mysqli->query($sql)) {

            if ($this->root == 'null') {
                $root = '';
            } else {
                $root = $this->root;
            }

            list($result, $data, $levels, $i, $row_no, $pnum) = $this->loadrecord(
                    $result, $data, $field_visi, $field_list, $row_id, $row_no, $show_id, $selected_val, $ids, $levels, $lv, $i, $mysqli, $sql,
                    $root, $this->key, $this->parent, $pnum, $p);
        }

        $this->levels = $levels;

        $this->pnum = $pnum;

        $page = [];
        $cntall = 0;
        if ($this->tree == 0) {

            if ($result = $mysqli->query($pagesql)) {
                while ($row = $result->fetch_assoc()) {
                    $cntall = $row['cnt'];
                }
            }
            $add = 0;
            if ($cnt != 0) {
                if ($cntall % $cnt != 0) {
                    $add = 1;
                }
                $cntrec = intdiv($cntall, $cnt) + $add;
            } else {
                $cntrec = 1;
            }

            $pnumber = 0;
            if (isset($this->pnumber)) {
                $pnumber = $this->pnumber; // current page number
            }

            $k = 0;
            if ($cnt != 0) {
                for ($i = 1; $i <= 3; $i++) {
                    if ($i <= $cntrec) {
                        $page[] = $i;
                        $k = $i;
                    }
                }
                for ($i = $pnumber - 2; $i <= $pnumber; $i++) {
                    if ($i > $k) {
                        $page[] = $i;
                        $k = $i;
                    }
                }
                for ($i = $pnumber + 1; $i <= $pnumber + 2; $i++) {
                    if ($i > $k && $i <= $cntrec) {
                        $page[] = $i;
                        $k = $i;
                    }
                }
                for ($i = $cntrec - 2; $i <= $cntrec; $i++) {
                    if ($i > $k) {
                        $page[] = $i;
                        $k = $i;
                    }
                }
            }
        }


        $this->footer = [];
        if ($this->sqlf != '') {
            if ($result = $mysqli->query($this->sqlf)) {
                while ($row = $result->fetch_assoc()) {
                    foreach ($field_visi as $j => $f) {
                        $txt = '';
                        foreach ($field_list as $jf => $fv) {
                            if ($fv['syn'] == $f) {
                                if (isset($fv['footertext'])) {
                                    $txt = $fv['footertext'];
                                }
                                break;
                            }
                        }
                        $this->footer[$f] = $txt . ' ' . $row[$f];
                    }
                }
            }
        }

        /*
          if ($cnt != 0) {
          for ($i = 1; $i <= $cntrec; $i++) {
          $page[] = $i;
          }
          } */

        return [$data, $row_no, $page];
    }

    public function Fields_SQL($info, $searchin) { // get select SQL
        $sql = '';
        $pagesql = '';
        //$field_list = [];
        $field_visi = [];
        $field_cap = [];
        $field_type = [];
        $fld = '';
        $fldf = '';
        $tab = '';
        $whe = '';
        $order = '';
        $joi = '';
        $on = '';
        $minno = 999999;
        $maxno = 0;
        $flds = [];
        $buttons = [];
        $hiddens = [];
        $ids = [];
        $show_id = '';
        $selected_val = '';
        $cnt = '0';
        $number = '1';
        $limit = '';
        $search = [];

        // $id_flds = [];
        //$all_flds = [];
        $_SESSION['info_' . $this->id] = $info;
        $this->info = $info;
        $sql = 'select <fields> from <tab> <where> <order> <limit>';
        $pagesql = 'select count(*) cnt from <tab> <where>';
        $sqlf = 'select <fields> from <tab> <where>';

        // options
        $tree = 0;
        $root = '';
        $key = '';
        $parent = '';
        foreach ($info as $i => $v) {
            if ($v['type'] == 'options') {
                if (isset($v['root'])) {
                    $tree = 1;
                    $root = $v['root'];
                    $key = $v['keysyn'];
                    $parent = $v['parentsyn'];
                }
                break;
            }
        }
        if (is_array($searchin)) {
            foreach ($searchin as $i => $v) {
                if ($v != '') {
                    $tree = 0;
                    break;
                }
            }
        }
        $this->tree = $tree;
        $this->root = $root;
        $this->key = $key;
        $this->parent = $parent;

        foreach ($info as $i => $v) {
            if ($v['type'] == 'search') {
                foreach ($v['fields'] as $j => $f) {
                    $search[] = $f;
                }
            }
            if ($v['type'] == 'page') {
                if ($tree == 0) {
                    if (isset($v['count']))
                        $cnt = $v['count'];
                    if (isset($v['number']))
                        $number = $v['number'];
                    $limit = ' limit ' . ($number - 1) * $cnt . ', ' . $cnt;
                    $this->pnumber = $number;
                }
            }
            if ($v['type'] == 'table') {
                if (isset($v['show_id']))
                    $show_id = $v['show_id'];
                if (isset($v['selected_val']))
                    $selected_val = $v['selected_val'];
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
                    if (isset($v['checkedval']))
                        $f['checkedval'] = $v['checkedval'];
                    if (isset($v['uncheckedval']))
                        $f['uncheckedval'] = $v['uncheckedval'];
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
            if ($v['type'] == 'order') {
                $order = $v['text'];
            }
            if ($v['type'] == 'button') {
                $buttons[] = ['class' => $v['class'], 'text' => $v['text']];
            }
            if ($v['type'] == 'hidden') {
                if (isset($v['value'])) {
                    $hiddens[] = ['id' => $v['id'], 'value' => $v['value']];
                } else {
                    $hiddens[] = ['id' => $v['id'], 'value' => ''];
                }
            }
        }

        //$k = $minno;
        $isfooter = 0;
        for ($i = $minno; $i <= $maxno; $i++) {
            //foreach ($flds as $j => $fv) {

            $f = $flds[$i];
            $syn = $f['tsyn'];
            if ($fld != '') {
                $fld = $fld . ', ';
            }
            $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];

            if ($fldf != '') {
                $fldf = $fldf . ', ';
            }
            if (isset($f['footer'])) {
                $fldf = $fldf . $f['footer'] . '(' . $syn . '.' . $f['name'] . ') as ' . $f['syn'];
                $isfooter = 1;
            } else {
                $fldf = $fldf . ' null as ' . $f['syn'];
            }


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

        //$field_list = [];
        foreach ($flds as $j => $f) {
            $isf = 0;
            for ($i = $minno; $i <= $maxno; $i++) {
                if ($i == $j) {
                    $isf = 1;
                    break;
                }
            }
            if ($isf == 0) {
                $f = $flds[$j];
                $syn = $f['tsyn'];
                if ($fld != '') {
                    $fld = $fld . ', ';
                }
                $fld = $fld . $syn . '.' . $f['name'] . ' as ' . $f['syn'];
                // $field_list[] = $f;
            }
        }

        $sql = str_replace('<fields>', $fld, $sql);
        $sql = str_replace('<tab>', $tab, $sql);
        $sql = str_replace('<where>', $whe, $sql);
        $sql = str_replace('<order>', $order, $sql);
        $sql = str_replace('<limit>', $limit, $sql);

        /*
          if ($this->tree == 0) {
          $sql = str_replace('<parent>', '', $sql);
          }
         */

        $pagesql = str_replace('<tab>', $tab, $pagesql);
        $pagesql = str_replace('<where>', $whe, $pagesql);

        if ($isfooter == 1) {
            $sqlf = str_replace('<fields>', $fldf, $sqlf);
            $sqlf = str_replace('<tab>', $tab, $sqlf);
            $sqlf = str_replace('<where>', $whe, $sqlf);
        }

        $this->sqlf = $sqlf;

        return [$sql, $pagesql, $flds /* $field_list */, $field_visi, $field_cap, $field_type, $buttons, $hiddens, $ids, $show_id, $selected_val, $cnt, $search];
    }

    public function getvertfirst($levels, $i) { // border-left: solid 1px black;
        $v1 = '';
        $v2 = 'border-left: solid 1px black;';
        for ($k = ($i + 1); $k <= count($levels) - 1; $k++) {
            if ($levels[$k] < $levels[$i]) {
                $v1 = '';
                $v2 = 'border-left: solid 1px black;';
                break;
            }
            if ($levels[$k] == $levels[$i]) {
                $v1 = 'border-left: solid 1px black;';
                $v2 = '';
                break;
            }
        }/**/
        return [$v1, $v2];
    }

    public function getvertnext($levels, $i, $lev) { // border-left: solid 1px black;
        $v1 = '';
        $v2 = '';
        for ($k = ($i + 1); $k <= count($levels) - 1; $k++) {
            if ($levels[$k] == $lev) {
                $v1 = 'border-left: solid 1px black;';
                $v2 = ''; // 'border-left: solid 1px black;';
                break;
            }
            if ($levels[$k] < $lev) {
                $v1 = '';
                $v2 = ''; // 'border-left: solid 1px black;';
                break;
            }
        }/**/
        return [$v1, $v2];
    }

    public function getplus($pnum, $i, $p) {
        $res = '';
        foreach ($pnum as $k => $v) {
            if ($v == $i) {
                $res = '<button class=plus p="' . $p . '" i="' . $i . '">-</button>';
                break;
            }
        }
        return $res;
    }

    public function JS_Html($js, $field_list, $field_visi, $field_cap, $field_type, $buttons, $hiddens, $row_no, $page) { // get html code
        $arr = json_decode($js, true);
        $style = '';
        $temp_html = '<div class=grid_cont><div id=' . $this->id . ' class=grid_class><<html>></div>';
        $html = '';
        $info = $this->info;
        foreach ($field_visi as $j => $f) {
            $isfld = 0;
            foreach ($field_list as $jf => $fli) {
                if ($fli['syn'] == $f) {
                    if (isset($fli['type']) && $fli['type'] == 'list') {
                        foreach ($field_list as $jf_2 => $fli_2) {
                            if ($fli['namecaption'] == $fli_2['syn']) {
                                $tsyn = $fli_2['tsyn'];
                                $name = $fli_2['name'];
                                break;
                            }
                        }
                    } else {
                        $tsyn = $fli['tsyn'];
                        $name = $fli['name'];
                    }
                    foreach ($this->search as $jj => $ff) {
                        $v = '';
                        if (isset($this->searchfld)) {
                            foreach ($this->searchfld as $sj => $sf) {
                                if ($sf == $ff) {
                                    $v = $this->searchval[$sj];
                                    break;
                                }
                            }
                        }
                        if ($tsyn . '.' . $name == $ff) {
                            if (isset($fli['type'])) {
                                if ($fli['type'] == 'select') {
                                    $options = '';
                                    foreach ($fli['options'] as $oj => $ov) {
                                        $selected = '';
                                        if ($v == $oj) {
                                            $selected = 'selected';
                                        }
                                        $options .= '<option ' . $selected . ' value="' . $oj . '">' . $ov . '</option>';
                                    }
                                    $html = $html . '<div class=search_cont col=' . $jf . '><select class=search_class value="' . $v . '" fld="' . $ff . '">' . $options . '</select></div>';
                                } else {
                                    $type = 'text';
                                    $checked = '';
                                    if (in_array($fli['type'], ['checkbox', 'date'])) {
                                        $type = $fli['type'];
                                        if ($v == 'Y') {
                                            $checked = 'checked';
                                        };
                                    }
                                    $html = $html . '<div class=search_cont col=' . $jf . '><input ' . $checked . ' type=' . $type . ' class=search_class value="' . $v . '" fld="' . $ff . '"></div>';
                                }
                            }
                            $isfld = 1;
                            break;
                        }
                    }
                    break;
                }
            }
            if ($isfld == 0) {
                $html = $html . '<div></div>';
            }
        }

        foreach ($field_cap as $j => $f) {
            $name = '';
            $ord = '';
            $sig = '';
            foreach ($field_list as $jf => $ff) {
                if ($field_visi[$j] == $ff['syn']) {
                    $name = $ff['tsyn'] . '.' . $ff['name'];
                    foreach ($this->info as $jf_2 => $fli_2) {
                        if (isset($fli_2['type'])) {
                            if ($fli_2['type'] == 'order') {
                                if ($fli_2['fld'] == $ff['tsyn'] . '.' . $ff['name']) {
                                    $ord = 'srt="' . $fli_2['ord'] . '"';
                                    $ords = '';
                                    if ($fli_2['ord'] == 'asc') {
                                        $ords = '&#9650;'; // '&uArr;';//'&and;';
                                    }
                                    if ($fli_2['ord'] == 'desc') {
                                        $ords = '&#9660;'; //'&dArr;';//&or;';
                                    }
                                    $sig = '<div class="search_sig" >' . $ords . '</div>';
                                    break;
                                }
                            }
                        }
                    }
                    break;
                }
            }
            $html = $html . '<div class=header_class fld="' . $name . '" ' . $ord . '>' . $sig . $f . '</div>';
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

                    if (in_array($field_type[$j], ['checkbox'])) {

                        $checkedval = 'Y';
                        $uncheckedval = 'N';
                        foreach ($field_list as $jf => $ff) {
                            if ($ff['syn'] == $f) {
                                $checkedval = $ff['checkedval'];
                                $uncheckedval = $ff['uncheckedval'];
                                break;
                            }
                        }

                        $element = 'input';
                        $fld[] = ['element' => $element, 'name' => $f, 'checkedval' => $checkedval, 'uncheckedval' => $uncheckedval, 'attr' => ['type' => $field_type[$j]]];
                    } else {
                        if (in_array($field_type[$j], ['file'])) {
                            $file = '';
                            foreach ($field_list as $jf => $ff) {
                                if ($ff['syn'] == $f) {
                                    $file = $ff['file'];
                                    break;
                                }
                            }
                            $element = 'file';
                            $fld[] = ['element' => $element, 'name' => $f, 'file' => $file, 'attr' => ['type' => $field_type[$j]]];
                        } else {

                            if (in_array($field_type[$j], ['date', 'number', 'string'])) {
                                $element = 'input';
                            }
                            if (in_array($field_type[$j], ['text'])) {
                                $element = 'textarea';
                            }
                            $fld[] = ['element' => $element, 'name' => $f, 'attr' => ['type' => $field_type[$j]]];
                        }
                    }
                }
            }
        }
        $html = $html . '<div id="json_f_' . $this->id . '" class="hidden">' . json_encode($fld) . '</div>';
        $style = '<style> #' . $this->id . '{display: grid; /*grid-auto-rows: auto 20px;*/ grid-template-columns: ' . $style . ';} </style>';

        foreach ($arr as $i => $r) {
            $k = 0;
            foreach ($r as $j => $f) {
                if ($j > 0) {
                    foreach ($field_visi as $k => $fv) {
                        if ($j - 1 == $k) {
                            $class = '';
                            if (is_array($f)) {
                                //$v = htmlspecialchars_decode($f['text']);
                                $v = $f['text'];
                            } else {
                                // $v = htmlspecialchars_decode($f);
                                $v = $f;
                                foreach ($field_list as $jf => $ff) {
                                    if ($ff['syn'] == $fv) {
                                        if (in_array($field_type[$k], ['checkbox'])) {
                                            if ($v == $ff['checkedval']) {
                                                $v = '&check;';
                                            }
                                            if ($v == $ff['uncheckedval'] || $v == '') {
                                                $v = '&#9744;';
                                            }
                                        } else {
                                            if (in_array($field_type[$k], ['file'])) {
                                                if ($f != '') {
                                                    $v = '<img src="' . $f . '" width=40 height=40>';
                                                } else {
                                                    $v = '';
                                                }
                                            }
                                        }
                                        if (isset($ff['halign'])) {
                                            if ($ff['halign'] == 'center') {
                                                $class = $class . ' halign_center';
                                            }
                                            if ($ff['halign'] == 'left') {
                                                $class = $class . ' halign_left';
                                            }
                                            if ($ff['halign'] == 'right') {
                                                $class = $class . ' halign_right';
                                            }
                                        }
                                        break;
                                    }
                                }
                            }
                            if ($k == 0 && $row_no == $i) {
                                $class = $class . ' Selected';
                            }
                            $stl = '';
                            $addt = '';
                            $wdt = 30;
                            if ($k == 0) {
                                if ($this->tree == 1) {
                                    //border:none;  border-left:solid 1px black;  
                                    //  if ($this->levels[$i] != 0) {
                                    $btn = '';
                                    $mrgn = 15;
                                    for ($ii = 1; $ii <= $this->levels[$i]; $ii++) {
                                        if ($ii == 1) {
                                            $btn = $this->getplus($this->pnum, $i, $this->pnum[$i]);
                                            if ($btn == '') {
                                                $mrgn = 0;
                                            }
                                            $v1 = '';
                                            $v2 = '';
                                            if ($this->levels[$i] == 1) {
                                                $addt .= '<div p="' . $this->pnum[$i] . '" i="' . $i . '" style="border:none;width:25px;left:-' . (($ii) * $wdt + $mrgn) . 'px;"  class="treearrow_v">' .
                                                        '<div p="' . $this->pnum[$i] . '"i="' . $i . '"   >' .
                                                        $btn .
                                                        '</div>' .
                                                        '</div>';
                                            } else {
                                                list($v1, $v2) = $this->getvertfirst($this->levels, $i); // border-left: solid 1px black;
                                                $addt .= '<div p="' . $this->pnum[$i] . '" i="' . $i . '" style="' . $v1 . 'width:25px;left:-' . (($ii) * $wdt + $mrgn) . 'px;"  class="treearrow_v">' .
                                                        '<div p="' . $this->pnum[$i] . '"i="' . $i . '" class="treearrow_h" style="' . $v2 . '" >' .
                                                        $btn .
                                                        '</div>' .
                                                        '</div>';
                                            }
                                        } else {
                                            $v1 = '';
                                            $v2 = '';
                                            list($v1, $v2) = $this->getvertnext($this->levels, $i, ($this->levels[$i] - $ii + 1)); // border-left: solid 1px black;
                                            $addt .= '<div p="' . $this->pnum[$i] . '" i="' . $i . '" style="' . $v1 . 'width:50px;left:-' . ($ii  * $wdt  + $mrgn) . 'px;"  class="treearrow_v"></div>';
                                        }
                                    }

                                    $stl = ' style="margin-left:' . (($this->levels[$i]-1) * $wdt + $mrgn + 10) . 'px;border:none; " ';
                                    // }
                                }
                            }
                            $html = $html . '<div p="' . $this->pnum[$i] . '" i="' . $i . '" ' . $stl . ' class="cell_class' . $class . '" col=' . $k . ' row=' . $i . '>' . $addt . $v . '</div>';
                            //$k = $k + 1;
                            break;
                        }
                    }
                }
            }
        }

        $inhtml = $style . $html;

        foreach ($this->footer as $j => $v) {
            $class = '';
            foreach ($field_list as $jf => $ff) {
                if ($ff['syn'] == $j) {
                    if (isset($ff['halign'])) {
                        if ($ff['halign'] == 'center') {
                            $class = $class . ' halign_center';
                        }
                        if ($ff['halign'] == 'left') {
                            $class = $class . ' halign_left';
                        }
                        if ($ff['halign'] == 'right') {
                            $class = $class . ' halign_right';
                        }
                    }
                    break;
                }
            }


            $inhtml .= '<div class="cell_footer' . $class . '">' . $v . '</div>';
        }

        $html = str_replace('<<html>>', $inhtml, $temp_html);

        $html = $html . '<div class=pager_cont id="pager_' . $this->id . '">';
        $this->inpager = '';
        $oldv = '';
        foreach ($page as $i => $v) {
            $addclass = '';
            if ($this->pnumber == $v) {
                $addclass = ' curpage';
            }
            if ($oldv != '' && $v != $oldv + 1) {
                $this->inpager = $this->inpager . '<div class="points">...</div>';
            }
            $oldv = $v;
            $this->inpager = $this->inpager . '<div class="pager' . $addclass . '" val=' . $v . '>' . $v . '</div>';
        }
        $html = $html . $this->inpager . '</div>';

        foreach ($buttons as $i => $v) {
            $html = $html . '<button class=' . $v['class'] . '>' . $v['text'] . '</button>';
        }

        foreach ($hiddens as $i => $v) {
            $html = $html . '<input id=' . $v['id'] . ' type=hidden value="' . $v['value'] . '"></input>';
        }

        $html = $html . '</div>';

        return [$inhtml, $html];
    }

    public function refresh($info, $search) {

        list(
                $this->sql,
                $this->pagesql,
                $this->field_list,
                $this->field_visi,
                $this->field_cap,
                $this->field_type,
                $this->buttons,
                $this->hiddens,
                $this->ids,
                $this->show_id,
                $this->selected_val,
                $this->cnt,
                $this->search
                ) = $this->Fields_SQL($info, $search);

        list(
                $this->data,
                $row_no,
                $this->page
                ) = $this->SQL_Data(
                $this->sql,
                $this->pagesql,
                $this->field_visi,
                $this->field_list,
                $this->ids,
                $this->show_id,
                $this->selected_val,
                $this->cnt);

        $this->js = $this->Data_JS($this->data);

        list(
                $this->inhtml,
                $this->html
                ) = $this->JS_Html(
                $this->js,
                $this->field_list,
                $this->field_visi,
                $this->field_cap,
                $this->field_type,
                $this->buttons,
                $this->hiddens,
                $row_no,
                $this->page
        );
    }

    // show grid 

    public function show() {

        $this->refresh($this->info, '');

        return $this->html;
    }
}
?>



