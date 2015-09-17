$(document).ready(function() {

    $("#declare").click(function() {

        var book_id = $("#book_id").val();
        var student_id = $("#student_id").val();
        var data = 'student_id=' + student_id + '&book_id=' + book_id + '&action=declare';

        $.ajax({
            type: "POST",
            url: "book_student.php",
            data: data,
            beforeSend: function(html) {
                $("#results").html('');
            },
            success: function(html) {
                $("#results").show();
                $("#results").append(html);

                // $("#declare").attr("disabled", "disabled");
                // $("#remove").removeAttr("disabled");
                $("#declare").attr("disabled", "disabled");
                $("#remove").attr("disabled", "disabled");
                
                $("#status").html('Σύγγραμμα έχει δηλωθεί: Ναι');

                // location.reload();
                window.setTimeout(function() {location.reload()}, 2000)
            }
        });

        return false;
    });

    $("#remove").click(function() {

        var book_id = $("#book_id").val();
        var student_id = $("#student_id").val();
        var data = 'student_id=' + student_id + '&book_id=' + book_id + '&action=remove';

        $.ajax({
            type: "POST",
            url: "book_student.php",
            data: data,
            beforeSend: function(html) {
                $("#results").html('');
            },
            success: function(html) {
                $("#results").show();
                $("#results").append(html);

                // $("#declare").removeAttr("disabled");
                // $("#remove").attr("disabled", "disabled");
                $("#declare").attr("disabled", "disabled");
                $("#remove").attr("disabled", "disabled");

                $("#status").html('Σύγγραμμα έχει δηλωθεί: Όχι');

                // location.reload();
                window.setTimeout(function() {location.reload()}, 2000)
            }
        });

        return false;
    });

    $("#available").click(function() {

        var book_id = $("#book_id").val();
        var publisher_id = $("#publisher_id").val();
        var data = 'publisher_id=' + publisher_id + '&book_id=' + book_id + '&action=available';

        $.ajax({
            type: "POST",
            url: "book_publisher.php",
            data: data,
            beforeSend: function(html) {
                $("#results").html('');
            },
            success: function(html) {
                $("#results").show();
                $("#results").append(html);

                // $("#available").attr("disabled", "disabled");
                // $("#unavailable").removeAttr("disabled");
                $("#available").attr("disabled", "disabled");
                $("#unavailable").attr("disabled", "disabled");

                $("#availability").html('Ναι');
                
                // location.reload();
                window.setTimeout(function() {location.reload()}, 2000)
            }
        });

        return false;
    });

    $("#unavailable").click(function() {

        var book_id = $("#book_id").val();
        var publisher_id = $("#publisher_id").val();
        var data = 'publisher_id=' + publisher_id + '&book_id=' + book_id + '&action=unavailable';

        $.ajax({
            type: "POST",
            url: "book_publisher.php",
            data: data,
            beforeSend: function(html) {
                $("#results").html('');
            },
            success: function(html) {
                $("#results").show();
                $("#results").append(html);

                // $("#available").removeAttr("disabled");
                // $("#unavailable").attr("disabled", "disabled");
                $("#available").attr("disabled", "disabled");
                $("#unavailable").attr("disabled", "disabled");

                $("#availability").html('Όχι');
                
                // location.reload();
                window.setTimeout(function() {location.reload()}, 2000)
            }
        });

        return false;
    });

});
