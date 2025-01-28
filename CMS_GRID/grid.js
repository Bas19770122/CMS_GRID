$(document).ready(function () {



    $('body').delegate('.grid_cont .cell_class', 'click', function (e) {

        elem = $(this);
        elem.parent().find('.cell_class').removeClass('Selected');
        elem.addClass('Selected');

        //  return false;
    });

    $('body').delegate('.grid_cont .delete', 'click', function (e) {


        function set_deleted(id) {
            var rw = JSON.parse($('#json_' + id).text());
            for (var key in rw)
            {
                if (rw[key][0]['type'] == 3)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('deleted');
            }
        }

        elem = $(this);
        id = elem.parent().find('.grid_class').eq(0).attr('id');

        rowno = elem.parent().find('.Selected').attr('row');

        jsn = JSON.parse($('#json_' + id).text());

        jsn[rowno][0]['type'] = 3;

        rcds = JSON.stringify(jsn);
        $('#json_' + id).empty();
        $('#json_' + id).append(rcds);

        set_deleted(id);


    });

    $('body').delegate('.grid_cont .insert', 'click', function (e) {

        elem = $(this);
        id = elem.parent().find('.grid_class').eq(0).attr('id');


        function set_inserted(id) {
            $('#' + id + ' .cell_class').removeClass('inserted');
            $('#' + id + ' .cell_class').removeClass('edited');
            var rw = JSON.parse($('#json_' + id).text());
            for (var key in rw)
            {
                if (rw[key][0]['type'] == 1)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('inserted');
                if (rw[key][0]['type'] == 2)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('edited');
            }
        }


        elem.prop('disabled', true);
        jsons = {
            id: id,
            action: 'insert',
            data: $('#json_' + id).text(),
            before: elem.attr('before'),
            after: elem.attr('after')

        };
        $.ajax({
            url: 'grid.php',
            method: 'POST',
            dataType: 'html',
            data: jsons
        }).done(function (data) {
            elem.prop('disabled', false);
            jsn = JSON.parse(data);
            rcd = jsn['js'];
            rcds = JSON.stringify(rcd);
            $('#json_' + id).empty();
            $('#json_' + id).append(rcds);
            nrcd = jsn['adddata'];
            //nrcds = JSON.stringify(nrcd);
            $('#' + id).append(nrcd);
            set_inserted(id);
        });


    });

    $('body').delegate('.grid_cont .save', 'click', function (e) {

        elem = $(this);
        id = elem.parent().find('.grid_class').eq(0).attr('id');

        elem.prop('disabled', true);
        jsons = {
            id: id,
            action: 'save',
            data: $('#json_' + id).text()
        };
        $.ajax({
            url: 'grid.php',
            method: 'POST',
            dataType: 'html',
            data: jsons
        }).done(function (data) {
            elem.prop('disabled', false);
            /* $('#json_' + id).empty();
             $('#json_' + id).append(data);         
             */
            jsn = JSON.parse(data);
            rcd = jsn['js'];
            rcds = JSON.stringify(rcd);
            $('#json_' + id).empty();
            $('#json_' + id).append(rcds);
            html = jsn['html'];
            $('#' + id).empty();
            $('#' + id).append(html);
            //set_edited(id);
        });


    });


    $('body').delegate('#cell_editor', 'focusout', function (e) {

        function set_edited(id) {
            $('#' + id + ' .cell_class').removeClass('edited');
            $('#' + id + ' .cell_class').removeClass('inserted');
            $('#' + id + ' .cell_class').removeClass('deleted');
            var rw = JSON.parse($('#json_' + id).text());
            for (var key in rw)
            {
                if (rw[key][0]['type'] == 1)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('inserted');
                if (rw[key][0]['type'] == 2)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('edited');
                if (rw[key][0]['type'] == 3)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('deleted');
            }
        }


        function save_cell(elem2, cont, txt, act, id) {
            var fld = JSON.parse($('#json_f_' + id).text());
            var data = JSON.parse($('#json_' + id).text());
            var ii = 0;
            var iscell = 0;
            var chcont = cont;//.replaceAll('"', '&quot;');
            for (var key in data)
            {
                var item = data[key];
                var jj = -1;
                for (var key2 in item)
                {
                    if (key2 != 0) {
                        jj = jj + 1; // numeric fields from 1                                                                     
                        if (parseInt(key2) - 1 == elem2.attr('col') && ii == elem2.attr('row')) {
                            var islist = fld[jj]['element'] == 'select' || fld[jj]['element'] == 'list';
                            if (chcont != (islist ? item[key2]['id'] : item[key2])) {
                                if (islist) { // list field
                                    if (act == 3) { // clear
                                        cont = '';
                                        txt = '';
                                    }
                                    item[key2]['id'] = cont;
                                    item[key2]['text'] = txt; //encodeURIComponent(txt);
                                    if (data[key][0]['type'] != 1) {
                                        data[key][0]['type'] = 2; // edited 
                                    }
                                    elem2.empty();
                                    elem2.append(txt);
                                    // save json to form
                                    $('#json_' + id).empty();
                                    js = JSON.stringify(data);
                                    $('#json_' + id).append(js);
                                    set_edited(id);
                                } else { // not list field 
                                    item[key2] = chcont; //encodeURIComponent(cont);  
                                    if (data[key][0]['type'] != 1) {
                                        data[key][0]['type'] = 2; // edited 
                                    }
                                    elem2.empty();
                                    if(fld[key2-1]['attr']['type'] == 'checkbox'){
                                        if(cont == fld[key2-1]['checkedval']){
                                         cont = '✓';  
                                        } else {
                                         cont = '☐';
                                        }                                        
                                    }
                                    elem2.append(cont);
                                    // save json to form
                                    $('#json_' + id).empty();
                                    js = JSON.stringify(data);
                                   // $('#json_' + id).append(js);
                                    $('#json_' + id).text(js);
                                    set_edited(id);

                                }
                                iscell = 1;
                                break;
                            } else {
                                iscell = 1;
                                break;
                            }
                        }
                    }
                }
                if (iscell == 1) {
                    break;
                }
                ii = ii + 1;
            }
            return data;
        }

        elem = $(this);
        elem2 = $(this).parent().eq(0);
        id = elem2.parent().attr('id');
        grid_id = $('#cell_editor').attr('grid_id');

        // save editoк text to grid  
        is_list = 0;
        act = 1; // select
        if ($('#cell_editor').find('#' + grid_id).length > 0) {
            grid = $('#cell_editor').find('#' + grid_id).eq(0);
            if ($('#cell_editor').find('#grid_list_id').length > 0) {
                is_list = 1;
                grid_list_id = $('#cell_editor').find('#grid_list_id').eq(0);
                grid_list_name = $('#cell_editor').find('#grid_list_name').eq(0);
                grid_list_action = $('#cell_editor').find('#grid_list_action').eq(0);
                cont = grid_list_id.val();
                txt = grid_list_name.val();
                act = grid_list_action.val();
            }
        }



        if (is_list == 0) {
            if (elem.attr('type') == 'checkbox') {
                if (elem.is(":checked")) {
                    cont = 'Y';
                } else {
                    cont = 'N';
                }
                txt = '';               
            } else {
                cont = elem.val();
                txt = elem.children('option:selected').text();
            }
        }



        // remove editor
        elem.remove();

        // cancel push 
        if (is_list == 1 && act == '0') {
            return false;
        }

        // save cell into json
        data = save_cell(elem2, cont, txt, act, id);

    });


    $('body').delegate('.cell_class', 'dblclick', function (e) {

        function get_editor_code(elem2, cont, id) {
            var data = JSON.parse($('#json_' + id).text());
            var fld = JSON.parse($('#json_f_' + id).text());
            var ii = 0;
            var iscell = 0;
            var elem_name = '';
            var attr_name = ' id=cell_editor';
            for (var key in fld)
            {
                if (elem2.attr('col') == key) {
                    var item = fld[key];
                    var is_list = 0;
                    for (var key2 in item) {
                        if (key2 == 'element') {
                            elem_name = item[key2];
                        }
                        if (key2 == 'attr') {
                            var attr = item[key2];
                            for (var key3 in attr) {
                                attr_name = attr_name + ' ' + key3 + '=' + attr[key3];
                                if (elem_name == 'input') {//(attr[key3] == 'date' || attr[key3] == 'number'){  
                                    if (attr['type'] == 'checkbox') {
                                        if (cont == '✓') {
                                            cont = item['checkedval'];
                                            attr_name = attr_name + ' checked ';
                                        }
                                        if (cont == '☐') {
                                            cont = item['uncheckedval'];
                                        }
                                        attr_name = attr_name +' style="left:-4px;top:1px;" ';
                                      //  attr_name = attr_name +' style="text-align: center;" ';
                                        
                                    }
                                    var chcont = cont.replaceAll('"', '&quot;');
                                    attr_name = attr_name + ' value="' + chcont + '"';
                                    cont = '';
                                }
                            }
                        }
                        if (key2 == 'options') {
                            var opt = item[key2];
                            cont = '';
                            for (var key3 in opt) {
                                sel = '';
                                if (data[elem2.attr('row')][parseInt(key) + 1]['id'] == key3) {
                                    sel = ' selected ';
                                }
                                cont = cont + '<option value=' + key3 + sel + '>' + opt[key3] + '</option>';
                            }
                        }
                        if (key2 == 'list') {
                            is_list = 1;
                            var lst = item[key2];
                            cont = '';
                            jsons = {
                                id: data[elem2.attr('row')][parseInt(key) + 1]['id'],
                                text: data[elem2.attr('row')][parseInt(key) + 1]['text'],
                                grid_id: id,
                                element: 'cell_editor'};
                            $.ajax({
                                url: lst,
                                method: 'POST',
                                dataType: 'html',
                                data: jsons
                            }).done(function (data) {
                                cell_editor = elem2.find('#cell_editor');
                                cell_editor.empty();
                                cell_editor.append(data);
                                grid_id = cell_editor.find('.grid_class').eq(0).attr('id');
                                cell_editor.attr('grid_id', grid_id);
                                //$('#cell_editor').empty();
                                //$('#cell_editor').append(data);
                            });
                        }
                    }
                    if (is_list == 0) {
                    }
                    return '<' + elem_name + ' ' + attr_name + '>' + cont + '</' + elem_name + '>';
                }
            }
            return '<textarea id=cell_editor>' + cont + '</textarea>';
        }

        var iseditor = 0;
        $('body').find('#cell_editor').each(function (index, value) {
            iseditor = 1;
        });
        // load editor
        if (iseditor == 0) {
            elem = $(this);
            cont = elem.text();
            id = elem.parent().attr('id');
            var editor = get_editor_code(elem, cont, id);
            elem.append(editor);
            $('#cell_editor').focus();
        }

        return false;
    });
});
