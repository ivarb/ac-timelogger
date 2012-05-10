<?php
require_once '../init.php';


// Set post defaults
$p = array(
    'p' => isset($_POST['project']) ? $_POST['project'] : '',
    'd' => isset($_POST['record_date']) ? $_POST['record_date'] : date('Y-m-d'),
    't' => isset($_POST['ticket']) && !empty($_POST['ticket']) ? (int) $_POST['ticket'] : '',
    'ti' => isset($_POST['time']) ? $_POST['time'] : '0:25',
    'b' => isset($_POST['body']) ? $_POST['body'] : '',
    'bc' => (count($_POST) && !isset($_POST['is_billable'])) ? '' : 'checked="yes"',
);

if (isset($_POST['log'])) {
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
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

</head>
<body>
    <div>
        <h2>API Status Information</h2>
        <?php
            foreach ($oApi->get('info') as $k => $v) {
                echo $k . ' => ' . $v . '<br />';
            }
        ?>
    </div>

    <form method="POST" action="">
        <h2>Log hour form</h2>

        <?php
        if (isset($error) && $error !== false) {
            echo "<p style=\"width:400px; padding:10px; background-color:red;color:white;\">$error</p>";
        }
        if (isset($succes) && !empty($succes)) {
            echo "<p style=\"width:400px; padding:10px; background-color:green;color:white;\">$succes</p>";
        }

        ?>



        Project:
        <select name="project">
            <option value="">- selecteer -</option>
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
        </select>

        <!-- Fields -->
        &nbsp;<br /><br />
        Date: <input name="record_date" style="width:80px;" type="text" value="<?php echo $p['d']; ?>" />&nbsp;
        Time: <input name="time" type="text" style="width:40px;" value="<?php echo $p['ti']; ?>" />&nbsp;&nbsp;
        Ticket: <input name="ticket" style="width:40px;" type="text" value="<?php echo $p['t']; ?>" />&nbsp;&nbsp;
        Body: <input name="body" type="text" style="width:450px;"  value="<?php echo $p['b']; ?>" />&nbsp;&nbsp;
        Billable? <input type="checkbox" name="is_billable" value="1" <?php echo $p['bc']; ?> /><br /><br />
        <input type="submit" name="log" value="log tijd" />
    </form>
</body>
</html>
