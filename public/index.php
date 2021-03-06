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
            $error = 'Could send request, please check all form fields.';
        } else {
            $succes = "<strong>Time record saved</strong> - ${p['ti']} hours @ ${p['b']} on ${p['d']}";

            // Reset some values
            $p['ti'] = '0.25';
            $p['b']  = '';
            $p['t']  = '';
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

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/main.js"></script>
</head>
<body>
    <form id="log" method="POST" action="" class="well">
        <h2>Active Collab - Simple Time Logger</h2>
        <p class="help-block">Use this to log time for projects, or individual tickets, in a fast and simple way.</p>
    <div>
        <br />
        <input name="record_date" size="10" type="text" value="<?php echo $p['d']; ?>" />&nbsp;
        <br /><br />
        <input type="text" name="filter" size="17" value="" placeholder="Filter projects" />&nbsp;&nbsp;
        <select name="project" id="projects">
            <option value="" class="default">- select a project -</option>
            <?php
            $projects = $oApi->get('projects');
            foreach($projects as $pr) {
    //            if ($pr['status'] !== 'active') {continue;}
                    $s = '';
                    if ($pr['id'] == $p['p']) {
                        $s = 'selected="selected"';
                    }
                    echo "<option value=\"{$pr['id']}\" $s>{$pr['name']}</option>";
                }
            ?>
        </select><br />
        <!-- Fields -->
        <input name="ticket" placeholder="# ticket" size="7" type="text" value="<?php echo $p['t']; ?>" />&nbsp;&nbsp;
        <input name="time" type="text" size="4" value="<?php echo $p['ti']; ?>" />&nbsp;&nbsp;
        <input name="body" placeholder="Task description..." type="text" style="width:350px;"  value="<?php echo $p['b']; ?>" />&nbsp;&nbsp;
        <label class="checkbox"><input type="checkbox" name="is_billable" value="1" <?php echo $p['bc']; ?> />Billable</label>
        <?php
        if (isset($error) && $error !== false) {
            echo "<p class=\"alert alert-error\">$error</p>";
        }
        if (isset($succes) && !empty($succes)) {
            echo "<p class=\"alert alert-success\">$succes</p>";
        }
        ?>
        <input type="submit" name="fake" value="fake" style="position:absolute;left:-9999px;width:0px;height:0px;">

        <a class="btn btn-success" id="submit" href="#">
        <i class="icon-time icon-white"></i>
        Log Time
        </a>
        <br clear="all" />
    </div>
    <div id="#tickets">&nbsp;</div>
    </form>
</body>
</html>
