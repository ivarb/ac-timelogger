<?php
require_once '../config.php';
require_once '../classes/Api.php';

set_time_limit(10);

if (!is_array($api) || empty($api['key'])) {
    die('Not valid');
}

// Setup api
$oApi = new Api($api);

// Test api
if ($oApi->testConnection() === false) {
    die('Api connection failed');
}
