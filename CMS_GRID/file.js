$(document).ready(function () {

    $('body').delegate('.grid_file', 'click', function (e) {
        var f = $(this);
        
        f.parent().attr('opening', 'Y');
        
         return true;
        
    });

    $('body').delegate('button.file_save', 'click', function (e) {


        var f = $(this).parent().find('[type=file]').eq(0);

        f.parent().attr('done', 'N');

        if (window.FormData === undefined) {

            alert('В вашем браузере FormData не поддерживается')

        } else {

            var formData = new FormData();
            formData.append('file', f[0].files[0]);

            $.ajax({
                type: "POST",
                url: '/upload.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                success: function (msg) {
                    if (msg.error == '') {
                        f.parent().attr('done', 'Y');
                        f.parent().attr('path', msg.success);
                    } else {
                    }
                }
            });

        }

        // $('#cell_editor').remove();
        $('#cell_editor').focusout();

        return true;

    });

    $('body').delegate('button.file_cancel', 'click', function (e) {

        $('#cell_editor').remove();
        return true;

    });


});

