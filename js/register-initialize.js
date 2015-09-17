$(document).ready(function() {

    $('.namedetails').show();
    $('.pubdetails').hide();
    $(".instsearch").show();
    $('.deptsearch').hide();

    $("#institution").val('');
    $("#department").val('');

    $("label.error").hide();

    $("#groupcombo").change(function() {

        switch ($("#groupcombo").val()) {
            case 'students':
            case 'secretariats':
                $('.namedetails').show();
                $('.pubdetails').hide();
                $(".instsearch").show();
                $(".deptsearch").hide();

                break;
            case 'publishers':
                $('.namedetails').hide();
                $('.pubdetails').show();
                $(".instsearch").hide();
                $(".deptsearch").hide();

                break;
            default:
                $('.namedetails').show();
                $('.pubdetails').show();
                $(".instsearch").hide();
                $(".deptsearch").hide();

                break;
        }

        $("#institution").val('');
        $("#department").val('');

        $("label.error").hide();

    });
});
