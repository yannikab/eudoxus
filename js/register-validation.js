$(document).ready(function() {
    $("#register-form").validate({
        rules: {
            username: {
                required: true,
                "remote":
                        {
                            url: 'validate_username.php',
                            type: "post",
                            data:
                                    {
                                        usernames: function()
                                        {
                                            return $('#username').val();
                                        }
                                    }
                        }
            },
            password: {
                required: true,
                minlength: 8
            },
            email: {
                required: true,
                email: true
            },
            lastname: "required",
            firstname: "required",
            pubname: "required",
            institution: {
                required: true,
                "remote":
                        {
                            url: 'validate_institution.php',
                            type: "post",
                            data:
                                    {
                                        institutions: function()
                                        {
                                            return $('#instsearch').val();
                                        }
                                    }
                        }
            },
            department: {
                required: true,
                "remote":
                        {
                            url: 'validate_department.php',
                            type: "post",
                            data:
                                    {
                                        departments: function()
                                        {
                                            return $('#deptsearch').val();
                                        }
                                    }
                        }
            },
        },
        messages: {
            username: {
                required: "Παρακαλώ εισάγετε το όνομα χρήστη",
                remote: jQuery.validator.format("Το όνομα χρησιμοποιείται ήδη, παρακαλώ επιλέξτε κάποιο άλλο")
            },
            password: {
                required: "Παρακαλώ εισάγετε το συνθηματικό",
                minlength: "Το συνθηματικό πρέπει να αποτελείται από τουλάχιστον 8 χαρακτήρες"
            },
            email: {
                required: "Παρακαλώ εισάγετε τη διεύθυνση ηλεκτρονικού ταχυδρομείου",
                email: "Μη έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου",
            },
            lastname: "Παρακαλώ εισάγετε το επώνυμο",
            firstname: "Παρακαλώ εισάγετε το όνομα",
            pubname: "Παρακαλώ εισάγετε την επωνυμία",
            institution: {
                required: "Παρακαλώ εισάγετε το ίδρυμα",
                remote: jQuery.validator.format("Μη έγκυρο όνομα ιδρύματος")
            },
            department: {
                required: "Παρακαλώ εισάγετε το τμήμα",
                remote: jQuery.validator.format("Μη έγκυρο όνομα τμήματος")
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});