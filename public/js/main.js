// On ready
$(document).ready(function() {
    try {
        // Form submit button
        $('#submit').click(function(e) {
            e.preventDefault();
            $("#log").submit();
        });

        // Project filter
        var $proj = $('#projects');
        var $back = $('#projects').find('option');
        $('input[name="filter"]').keyup(function(e)
        {
            $proj.val($back.css('display','block').val());
            var val = $(this).val();
            if (val == '') {
                return;
            }

            // regex
            var reg = new RegExp(val, "i");
            $proj.find('option').filter(function(i)
            {
                var $t = $(this);
                if (!$t.hasClass('default') && $t.html().match(reg) === null) {
                    $t.css('display', 'none');
                } else {
                    $proj.val($t.val());
                }
            });
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
