<?php
$system_path = 'system';
define('BASEPATH', str_replace('\\', '/', $system_path));

require_once 'application/config/config.php';
require_once BASEPATH.'/Global_functions.php';
require_once BASEPATH.'/Bootstrap.php';


$bootstrap = new Bootstrap();
