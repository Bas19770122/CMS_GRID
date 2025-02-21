$(document).ready(function () {

    $('body').delegate('#id_tree_1 .cell_class', 'click', function (e) {


        var data = JSON.parse($('#json_id_tree_1').text());
        for (var key in data)
        {
            if ($(this).attr('row') == key) {
                var item = data[key];
                $('#grid_list_id').attr('value', item[0]['id']);
                $('#grid_list_name').attr('value', item[1]);
                break;
            }
        }

      //  $('#id_list_1 .cell_class').removeClass('Selected');
      //  $('#id_list_1 .cell_class[row=' + $(this).attr('row') + ']').addClass('Selected');

      return false;

    });


    $('body').delegate('button.select', 'click', function (e) {

        $('#cell_editor').focusout();
        return true;


    });

    $('body').delegate('button.cancel', 'click', function (e) {

        $('#grid_list_id').attr('value', '');
        $('#grid_list_name').attr('value', '');
        $('#grid_list_action').attr('value', '0'); // 0-cancel, 1-select, 2-edit, 3-clear
        $('#cell_editor').focusout();
        return true;


    });

    $('body').delegate('button.clear', 'click', function (e) {

        $('#grid_list_id').attr('value', '');
        $('#grid_list_name').attr('value', '');
        $('#grid_list_action').attr('value', '3'); // 0-cancel, 1-select, 2-edit, 3-clear    
        $('#cell_editor').focusout();
        return true;


    });

});
