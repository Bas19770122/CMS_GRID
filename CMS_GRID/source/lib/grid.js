$(document).ready(function () {

    function setvistree(id, i, tp) {
        if (tp == 'visible') {
            $($('#' + id + ' *[p=' + i + ']').get().reverse()).each(function () {
                $(this).removeClass('invis');
                if ($(this).hasClass('plus')) {
                    $(this).text('+');
                    let i = $(this).attr('i');
                    setvistree(id, i);
                }
            });
        } else {
            $($('#' + id + ' *[p=' + i + ']').get()).each(function () {
                if ($(this).hasClass('plus')) {
                    let i = $(this).attr('i');
                    setvistree(id, i);
                }
                $(this).addClass('invis');
            });
        }
    }

    $('body').delegate('.plus', 'dblclick', function (e) {

        return false;

    });

    $('body').delegate('.plus', 'click', function (e) {

        let i = $(this).attr('i');
        let grid = $(this).parent().parent().parent().parent().parent().find('.grid_class').eq(0);
        let id = grid.attr('id');

        if ($(this).text() == '-') {
            setvistree(id, i, 'hidden');
            $(this).text('+');
            /*$(this).parent().parent().find('.treearrow_h2').css('visibility', 'hidden');*/
        } else {
            setvistree(id, i, 'visible');
            $(this).text('-');
            /*$(this).parent().parent().find('.treearrow_h2').css('visibility', 'visible');*/
        }

        return false;

    });

    function getsrt(elem) {

        elem.parent().find('.header_class').each(function () {
            if ($(this).attr('col') != elem.attr('col')) {
                $(this).attr('srt', '');
            }
        });

        let srt = elem.attr('srt');
        if (srt == null || srt == '') {
            srt = 'asc';
        } else {
            if (srt == 'asc') {
                srt = 'desc';
            } else {
                srt = '';
            }
        }

        return srt;
    }

    $('body').delegate('.header_class', 'click', function (e) {

        let elem = $(this);
        let fld = $(this).attr('fld');

        let srt = getsrt(elem);

        $(this).attr('srt', srt);
        let grid = elem.parent().parent().find('.grid_class').eq(0);
        let id = grid.attr('id');
        let num = '1';

        let cont = $('#' + id).find('.search_cont').parent().eq(0);

        let [searchlst, searchfldlst] = getsearchlist(cont);

        let searchlst_s = JSON.stringify(searchlst);
        let searchfldlst_s = JSON.stringify(searchfldlst);

        jsons = {
            id: id,
            action: 'refresh',
            data: $('#json_' + id).text(),
            number: num,
            search: searchlst_s, //elem.val(),
            searchfld: searchfldlst_s, //elem.attr('fld')
            sortfld: fld,
            sorttp: srt
        };
        $.ajax({
            url: 'source/lib/grid.php',
            method: 'POST',
            dataType: 'html',
            data: jsons
        }).done(function (data) {
            jsn = JSON.parse(data);
            rcd = jsn['js'];
            rcds = JSON.stringify(rcd);
            $('#json_' + id).empty();
            $('#json_' + id).append(rcds);
            html = jsn['html'];
            $('#' + id).empty();
            $('#' + id).append(html);
            pgr = jsn['pager'];
            $('#pager_' + id).empty();
            $('#pager_' + id).append(pgr);
        });

        return true;
    });



    $('body').delegate('.search_class', 'focusout', function (e) {
        return false;
    });

    function getsearchlist(cont) {
        let searchlst = [];
        let searchfldlst = [];
        cont.find('.search_class').each(function () {
            if ($(this).attr('type') == 'checkbox') {
                if ($(this).is(':checked')) {
                    searchlst.push('Y');
                } else {
                    searchlst.push('');
                }
            } else {
                searchlst.push($(this).val());
            }
            searchfldlst.push($(this).attr('fld'));
        });
        return [searchlst, searchfldlst];
    }


    $('body').delegate('.search_class', 'change', function (e) {

        let elem = $(this);
        let grid = elem.parent().parent().parent().find('.grid_class').eq(0);
        let id = grid.attr('id');
        let num = '1';

        let cont = $('#' + id).find('.search_cont').parent().eq(0);

        /*
         let searchlst = [];
         let searchfldlst = [];
         
         cont.find('.search_class').each(function () {
         if($(this).attr('type')=='checkbox'){
         if($(this).is(':checked')) {
         searchlst.push('Y');      
         } else {
         searchlst.push('');      
         }
         } else {
         searchlst.push($(this).val());   
         }            
         searchfldlst.push($(this).attr('fld'));
         });
         */

        let [searchlst, searchfldlst] = getsearchlist(cont);

        let searchlst_s = JSON.stringify(searchlst);
        let searchfldlst_s = JSON.stringify(searchfldlst);

        let srt = '';
        let fld = '';
        elem.parent().parent().find('.header_class').each(function () {
            if ($(this).attr('srt') != '' && $(this).attr('srt') != undefined) {
                srt = $(this).attr('srt');
                fld = $(this).attr('fld');
            }
        });

        jsons = {
            id: id,
            action: 'refresh',
            data: $('#json_' + id).text(),
            number: num,
            search: searchlst_s, //elem.val(),
            searchfld: searchfldlst_s, //elem.attr('fld')
            sortfld: fld,
            sorttp: srt
        };
        $.ajax({
            url: 'source/lib/grid.php',
            method: 'POST',
            dataType: 'html',
            data: jsons
        }).done(function (data) {
            jsn = JSON.parse(data);
            rcd = jsn['js'];
            rcds = JSON.stringify(rcd);
            $('#json_' + id).empty();
            $('#json_' + id).append(rcds);
            html = jsn['html'];
            $('#' + id).empty();
            $('#' + id).append(html);
            pgr = jsn['pager'];
            $('#pager_' + id).empty();
            $('#pager_' + id).append(pgr);
        });

        return true;
    });


    $('body').delegate('.pager', 'click', function (e) {

        let elem = $(this);
        let grid = elem.parent().parent().find('.grid_class').eq(0);
        let id = grid.attr('id');
        let num = elem.attr('val');

        let cont = $('#' + id).find('.search_cont').parent().eq(0);

        /*
         var searchlst = [];
         var searchfldlst = [];
         cont.find('.search_class').each(function () {
         searchlst.push($(this).val());
         searchfldlst.push($(this).attr('fld'));
         });
         */

        let [searchlst, searchfldlst] = getsearchlist(cont);

        let searchlst_s = JSON.stringify(searchlst);
        let searchfldlst_s = JSON.stringify(searchfldlst);

        let srt = '';
        let fld = '';
        elem.parent().parent().find('.header_class').each(function () {
            if ($(this).attr('srt') != '' && $(this).attr('srt') != undefined) {
                srt = $(this).attr('srt');
                fld = $(this).attr('fld');
            }
        });

        jsons = {
            id: id,
            action: 'refresh',
            data: $('#json_' + id).text(),
            number: num,
            search: searchlst_s, //elem.val(),
            searchfld: searchfldlst_s, //elem.attr('fld')       
            sortfld: fld,
            sorttp: srt
        };

        $.ajax({
            url: 'source/lib/grid.php',
            method: 'POST',
            dataType: 'html',
            data: jsons
        }).done(function (data) {
            jsn = JSON.parse(data);
            rcd = jsn['js'];
            rcds = JSON.stringify(rcd);
            $('#json_' + id).empty();
            $('#json_' + id).append(rcds);
            html = jsn['html'];
            $('#' + id).empty();
            $('#' + id).append(html);
            pgr = jsn['pager'];
            $('#pager_' + id).empty();
            $('#pager_' + id).append(pgr);
        });

        //  return false;
    });

    $('body').delegate('.grid_cont .cell_class', 'click', function (e) {

        var elem = $(this);
        elem.parent().find('.cell_class').removeClass('Selected');
        elem.addClass('Selected');

        //  return false;
    });

    function set_next_focus(id, rowno, colno) {
        var rw = JSON.parse($('#json_' + id).text());
        for (var key in rw)
        {
            if (key == rowno) {
                $('#' + id + ' .cell_class').removeClass('Selected');
                $('#' + id + ' .cell_class[row=' + parseInt(key) + '].cell_class[col=' + colno + ']').addClass('Selected');
                break;
            }
        }
    }

    $('body').delegate('.grid_cont .refresh', 'click', function (e) {

        var elem = $(this);
        var id = elem.parent().find('.grid_class').eq(0).attr('id');
        if (elem.parent().find('#pager_' + id).length > 0) {
            var pager = elem.parent().find('#pager_' + id).eq(0);
            if (pager.find('.curpage').length > 0) {
                var num = pager.find('.curpage').eq(0).attr('val');
            }
        }

        let cont = $('#' + id).find('.search_cont').parent().eq(0);

        let [searchlst, searchfldlst] = getsearchlist(cont);

        var searchlst_s = JSON.stringify(searchlst);
        var searchfldlst_s = JSON.stringify(searchfldlst);

        let srt = '';
        let fld = '';
        elem.parent().parent().find('.header_class').each(function () {
            if ($(this).attr('srt') != '' && $(this).attr('srt') != undefined) {
                srt = $(this).attr('srt');
                fld = $(this).attr('fld');
            }
        });

        elem.prop('disabled', true);
        jsons = {
            id: id,
            action: 'refresh',
            data: $('#json_' + id).text(),
            number: num,
            search: searchlst_s, //elem.val(),
            searchfld: searchfldlst_s, //elem.attr('fld')       
            sortfld: fld,
            sorttp: srt
        };
        $.ajax({
            url: 'source/lib/grid.php',
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
            html = jsn['html'];
            $('#' + id).empty();
            $('#' + id).append(html);
            pgr = jsn['pager'];
            $('#pager_' + id).empty();
            $('#pager_' + id).append(pgr);
        });


    });


    function set_deleted(id) {
        var rw = JSON.parse($('#json_' + id).text());
        for (var key in rw)
        {
            if (rw[key][0]['type'] == 3)
                $('#' + id + ' .cell_class[row=' + parseInt(key) + ']').addClass('deleted');
        }
    }


    $('body').delegate('.grid_cont .delete', 'click', function (e) {

        elem = $(this);
        id = elem.parent().find('.grid_class').eq(0).attr('id');

        rowno = elem.parent().find('.Selected').attr('row');
        colno = elem.parent().find('.Selected').attr('col');

        jsn = JSON.parse($('#json_' + id).text());

        jsn[rowno][0]['type'] = 3;

        rcds = JSON.stringify(jsn);
        $('#json_' + id).empty();
        $('#json_' + id).append(rcds);

        set_deleted(id);

        set_next_focus(id, parseInt(rowno) + 1, colno);

        return false;

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
            url: 'source/lib/grid.php',
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
            if ($('#' + id).find('.cell_footer').length > 0) {
                $(nrcd).insertBefore('#' + id + ' .cell_footer:first');
            } else {
                $('#' + id).append(nrcd);
            }
            set_inserted(id);
        });

        return false;
    });

    $('body').delegate('.grid_cont .save', 'click', function (e) {

        var elem = $(this);
        var id = elem.parent().find('.grid_class').eq(0).attr('id');
        if (elem.parent().find('#pager_' + id).length > 0) {
            var pager = elem.parent().find('#pager_' + id).eq(0);
            if (pager.find('.curpage').length > 0) {
                var num = pager.find('.curpage').eq(0).attr('val');
            }
        }

        let cont = $('#' + id).find('.search_cont').parent().eq(0);

        let [searchlst, searchfldlst] = getsearchlist(cont);

        var searchlst_s = JSON.stringify(searchlst);
        var searchfldlst_s = JSON.stringify(searchfldlst);

        let srt = '';
        let fld = '';
        elem.parent().parent().find('.header_class').each(function () {
            if ($(this).attr('srt') != '' && $(this).attr('srt') != undefined) {
                srt = $(this).attr('srt');
                fld = $(this).attr('fld');
            }
        });

        elem.prop('disabled', true);
        jsons = {
            id: id,
            action: 'save',
            data: $('#json_' + id).text(),
            number: num,
            search: searchlst_s, //elem.val(),
            searchfld: searchfldlst_s, //elem.attr('fld')       
            sortfld: fld,
            sorttp: srt
        };
        $.ajax({
            url: 'source/lib/grid.php',
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
            pgr = jsn['pager'];
            $('#pager_' + id).empty();
            $('#pager_' + id).append(pgr);
            //set_edited(id);
        });

        return false;
    });


    $('body').delegate('#cell_editor', 'focusout', function (e) {

        elem = $(this);
        elem2 = $(this).parent().eq(0);

        txt = elem2.prop('outerHTML');

        elms = $(txt).find('#cell_editor').remove().end();

        txt = elms.prop('outerHTML');

        var vtree = '';
        $(txt).find('.treearrow_v').each(function (index) {
            vtree = vtree + $(this).prop('outerHTML');

        });

        // var vtree = elem2.html();

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

        file_id = $('#cell_editor').attr('file_id');

        // save editoк text to grid  
        is_file = 0;
        actf = '0';
        if ($('#cell_editor').find('#' + file_id).length > 0) {
            is_file = 1;
            contaner = $('#cell_editor').find('#' + file_id).eq(0);
            if (contaner.attr('done') == 'Y') {
                if ($('#cell_editor').find('.grid_file').length > 0) {
                    //grid_file = $('#cell_editor').find('.grid_file').eq(0);
                    //cont = grid_file.val();
                    //cont = cont.split(/(\\|\/)/g).pop();
                    cont = contaner.attr('path');
                    txt = '';
                    actf = '2';
                }
            }
            if (contaner.attr('opening') == 'Y') {
                contaner.attr('opening', 'N');
                actf = '0';
            }
        }

        if (is_list == 0 && is_file == 0) {
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

        // cancel push 
        if (is_file == 1 && actf == '0') {
            return false;
        }

        // remove editor
        elem.remove();

        // cancel push 
        if (is_list == 1 && act == '0') {
            return false;
        }

        // save cell into json
        data = save_cell(elem2, cont, txt, act, id, vtree);

        return false;

    });


    $('body').delegate('.cell_class', 'dblclick', function (e) {

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
            //element = element.offsetParent;
           /* var offset = elem.offset();
            if (!elem.hasClass('rel_pos')) {
              $('#cell_editor').css({'left': offset.left + 'px'});
              $('#cell_editor').css({'top': offset.top + 'px'});
            }*/            
        }

        return false;
    });


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


    function save_cell(elem2, cont, txt, act, id, tree) {
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
                                elem2.append(tree + txt);
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
                                if (fld[key2 - 1]['attr']['type'] == 'checkbox') {
                                    if (cont == fld[key2 - 1]['checkedval']) {
                                        cont = '✓';
                                    } else {
                                        cont = '☐';
                                    }
                                }
                                if (fld[key2 - 1]['attr']['type'] == 'file' && cont != '') {
                                    elem2.append(tree + '<img src=' + cont + ' width=40 height=40 >');
                                } else {
                                    elem2.append(tree + cont);
                                }
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
                                    attr_name = attr_name + ' style="left:-4px;top:1px;" ';
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
                            var offset = elem2.offset();
                            cell_editor.css({'left': offset.left + 'px'});
                            cell_editor.css({'top': offset.top + 'px'});    
                            elem2.removeClass('rel_pos');
                            //$('#cell_editor').empty();
                            //$('#cell_editor').append(data);
                        });
                    }
                    if (key2 == 'file') {
                        var fil = item[key2];
                        cont = '';
                        jsons = {
                            path: data[elem2.attr('row')][parseInt(key) + 1],
                            name: item['name'],
                            grid_id: id,
                            element: 'cell_editor'};
                        $.ajax({
                            url: 'source/lib/' + fil,
                            method: 'POST',
                            dataType: 'html',
                            data: jsons
                        }).done(function (data) {
                            cell_editor = elem2.find('#cell_editor');
                            cell_editor.empty();
                            cell_editor.append(data);
                            file_id = cell_editor.find('.file_class').eq(0).attr('id');
                            cell_editor.attr('file_id', file_id);
                            var offset = elem2.offset();
                            cell_editor.css({'left': offset.left + 'px'});
                            cell_editor.css({'top': offset.top + 'px'});
                            elem2.removeClass('rel_pos');
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


});
