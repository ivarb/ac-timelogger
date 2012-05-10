<?php
require_once '../init.php';


// Set post defaults
$p = array(
    'p' => isset($_POST['project']) ? $_POST['project'] : '',
    'd' => isset($_POST['record_date']) ? $_POST['record_date'] : date('Y-m-d'),
    't' => isset($_POST['ticket']) && !empty($_POST['ticket']) ? (int) $_POST['ticket'] : '',
    'ti' => isset($_POST['time']) ? $_POST['time'] : '0.25',
    'b' => isset($_POST['body']) ? $_POST['body'] : '',
    'bc' => (count($_POST) && !isset($_POST['is_billable'])) ? '' : 'checked="yes"',
);

if (count($_POST)) {
    $error = false;
    if (empty($_POST['project'])) {
        $error = 'Geen project geselecteerd';
    }

    if ($error === false) {
        // Fine, log time
        $res = $oApi->logTime(
            $api['user_id'],
            $p['p'],
            $p['ti'],
            $p['b'],
            $p['d'],
            $p['bc'],
            (!empty($p['t']) ? $p['t'] : false)
        );
        if ($res === false) {
            $error = 'Verzenden niet gelukt, controleer de velden';
        } else {
            $succes = 'Tijd opgeslagen';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Active Collab - API</title>

    <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/css/main.css" type="text/css" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/main.js"></script>

</head>
<body>
    <form id="log" method="POST" action="" class="well">
        <h2>Angry Bytes - Uren registratie</h2>
        <p class="help-block">Gebruik dit formulier om uren te registreren op projecten, of tickets binnen projecten op Active Collab</p>

    <div>
        <br />
        <select name="project">
            <option value="">- selecteer een project -</option>
            <?php
            $projects = $oApi->get('projects');
                foreach($projects as $pr) {
                    $s = '';
                    if ($pr['id'] == $p['p']) {
                        $s = 'selected="selected"';
                    }
                    echo $s;
                    echo "<option value=\"{$pr['id']}\" $s>{$pr['name']}</option>";
                }
            ?>
        </select>&nbsp;&nbsp;
        <input name="ticket" placeholder="# ticket" size="7" type="text" value="<?php echo $p['t']; ?>" /><br />
        <!-- Fields -->
        <input name="record_date" size="10" type="text" value="<?php echo $p['d']; ?>" />&nbsp;
        <input name="time" type="text" size="4" value="<?php echo $p['ti']; ?>" />&nbsp;&nbsp;
        <input name="body" placeholder="Omchrijving..." type="text" style="width:350px;"  value="<?php echo $p['b']; ?>" />&nbsp;&nbsp;
        <label class="checkbox"><input type="checkbox" name="is_billable" value="1" <?php echo $p['bc']; ?> />&nbsp; Billable?</label>
        <?php
        if (isset($error) && $error !== false) {
            echo "<p class=\"alert alert-error\">$error</p>";
        }
        if (isset($succes) && !empty($succes)) {
            echo "<p class=\"alert alert-success\">$succes</p>";
        }
        ?>
        <a class="btn btn-success" id="submit" href="#">
        <i class="icon-time icon-white"></i>
        Log Time
        </a>
        <br clear="all" />
    </div>
    </form>
</body>
</html>
