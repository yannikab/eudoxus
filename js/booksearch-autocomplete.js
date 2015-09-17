$(document).ready(function() {
    $('#department').autocomplete({
        source: 'json_departments.php',
        minLength: 1,
        select: function(evt, ui) {
            evt.preventDefault();
            $('#department').val(ui.item.value);
        }
    });

    $('#publisher').autocomplete({
        source: 'json_publishers.php',
        minLength: 1,
        select: function(evt, ui) {
            evt.preventDefault();
            $('#publisher').val(ui.item.value);
        }
    });
});