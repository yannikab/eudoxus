$(document).ready(function() {
    $('#institution').autocomplete({
        source: 'json_institutions.php',
        minLength: 1,
        select: function(evt, ui) {
            evt.preventDefault();
            $('#institution').val(ui.item.value);
            $("#register-form").validate().element("#institution");

//            $('#inst_id').val(ui.item.id);

            $('#department').val('');
            $('.deptsearch').show();
//            $('#department').focus();

//            $('#dept_id').val('');

            $('#department').autocomplete({
                source: 'json_departments.php' + '?inst_id=' + ui.item.id,
                minLength: 1,
                select: function(evt, ui) {
                    evt.preventDefault();
                    $('#department').val(ui.item.value);
                    $("#register-form").validate().element("#department");

//                    $('#dept_id').val(ui.item.id);
                }
            });
        }
    });
});