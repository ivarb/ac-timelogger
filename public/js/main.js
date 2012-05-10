// On ready
$(document).ready(function() {
    try {
        // Form submit button
        $('#submit').click(function(e) {
            e.preventDefault();
            $("#log").submit();
        });

        /*
        // Proj change
        $('#projects').change(function() {
            var $tick = $('#tickets');
            $tick.empty().fadeOut('slow');

            var id = $(this).find(':selected').val();
            if (typeof(id) == 'string' && id > 0) {
                $.get('time.php', 'project_id=' + id, function(data) {
                    $tick.html(data.html).fadeIn('fast');
                });
            }
        });
        */

        // Time hide alert
        // setTimeout("$('.alert-success').fadeOut('slow')", 1000);
    } catch (e) {
        // BooBoo :'(
    }
});
