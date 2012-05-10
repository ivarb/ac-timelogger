// On ready
$(document).ready(function() {
    try {
        // Form submit button
        $('#submit').click(function(e) {
            e.preventDefault();
            $("#log").submit();
        });

        // Time hide alert
        setTimeout("$('.alert-success').fadeOut('slow')", 1000);
    } catch (e) {
        // BooBoo :'(
    }
});
