$(document).ready(function() {

    $("#submit").click(function() {

        var code = encodeURIComponent($("#code").val());
        var title = encodeURIComponent($("#title").val());
        var author = encodeURIComponent($("#author").val());
        var isbn = encodeURIComponent($("#isbn").val());
        var publisher = encodeURIComponent($("#publisher").val());
        var department = encodeURIComponent($("#department").val());
        var sort = encodeURIComponent($("#sort").val());
        var dir = encodeURIComponent($("#dir").val());

        var data =
                'code=' + code +
                '&title=' + title + 
                '&author=' + author + 
                '&isbn=' + isbn + 
                '&publisher=' + publisher + 
                '&department=' + department + 
                '&sort=' + sort + 
                '&dir=' + dir;

        $.ajax({
            type: "POST",
            url: "booksearch_results.php",
            data: data,
            beforeSend: function(html) {
                $("#results").html('<div class="resultscount"><p class="notify">Γίνεται αναζήτηση...</p></div>');
            },
            success: function(html) {
                // $("#results").show();
                $("#results").html('');
                $("#results").append(html);
            }
        });

        return false;
    });
});
