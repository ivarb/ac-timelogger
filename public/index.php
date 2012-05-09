<?php
require_once '../config.php';
require_once '../classes/Api.php';

if (!is_array($api) || empty($api['key'])) {
    die('Not valid');
}

$api = new Api($api);

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Active Collab - API</title>
</head>
<body>
</body>
</html>
