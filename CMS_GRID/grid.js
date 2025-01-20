$(document).ready(function () {

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

/*
        function set_edited(id) {
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
        */
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
            var rw = JSON.parse($('#json_' + id).text());
            for (var key in rw)
            {
                if (rw[key][0]['type'] == 2)
                    $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('edited');
            }
        }


        function save_cell(elem2, cont, txt, act, id) {
            var fld = JSON.parse($('#json_f_' + id).text());
            var data = JSON.parse($('#json_' + id).text());
            var ii = 0;
            var iscell = 0;
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
                            if (cont != (islist ? item[key2]['id'] : item[key2])) {
                                if (islist) { // list field
                                    if (act == 3) { // clear
                                        cont = '';
                                        txt = '';
                                    }
                                    item[key2]['id'] = cont;
                                    item[key2]['name'] = txt; //encodeURIComponent(txt);
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
                                    item[key2] = cont; //encodeURIComponent(cont);  
                                    if (data[key][0]['type'] != 1) {
                                      data[key][0]['type'] = 2; // edited 
                                    }
                                    elem2.empty();
                                    elem2.append(cont);
                                    // save json to form
                                    $('#json_' + id).empty();
                                    js = JSON.stringify(data);
                                    $('#json_' + id).append(js);
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

        // save editoк text to grid  
        is_list = 0;
        act = 1; // select
        $('#cell_editor').find('#' + elem2.attr('list_id') + '_id').each(function (index, value) {
            is_list = 1;
            cont = $('#cell_editor #' + elem2.attr('list_id') + '_id').attr('value');
            txt = $('#cell_editor #' + elem2.attr('list_id') + '_name').attr('value');
            act = $('#cell_editor #' + elem2.attr('list_id') + '_action').attr('value');
        });



        if (is_list == 0) {
            cont = elem.val();
            txt = elem.children('option:selected').text();
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
                                    attr_name = attr_name + ' value=' + cont;
                                    cont = '';
                                }
                            }
                        }
                        if (key2 == 'options') {
                            var opt = item[key2];
                            cont = '';
                            for (var key3 in opt) {
                                sel = '';
                                if (data[elem2.attr('row')][key - 1]['id'] == key3) {
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
                                id: data[elem2.attr('row')][key - 1]['id'],
                                name: data[elem2.attr('row')][key - 1]['name'],
                                grid_id: id,
                                element: 'cell_editor'};
                            $.ajax({
                                url: lst,
                                method: 'POST',
                                dataType: 'html',
                                data: jsons
                            }).done(function (data) {
                                $('#cell_editor').empty();
                                $('#cell_editor').append(data);
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


    });
});
