// On ready
$(document).ready(function() {
    try {
        $('#submit').click(function(e) {
            e.preventDefault();
            $("#log").submit();
        });
    } catch (e) {
        // BooBoo :'(
    }
});
