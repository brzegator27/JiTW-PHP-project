<?php
$system_path = 'system';
define('BASEPATH', str_replace('\\', '/', $system_path));

require_once 'application/config/config.php';
require_once BASEPATH.'/Global_functions.php';
require_once BASEPATH.'/Bootstrap.php';


$bootstrap = new Bootstrap();

//echo $_GET['path'];
//
//echo BASEPATH;
//br();
//echo 'host'.$_SERVER['HTTP_HOST'];
//br();
//echo 'self' . $_SERVER['PHP_SELF'];
//br();
//echo 'request_uri'.filter_input(INPUT_SERVER, $_SERVER['REQUEST_URI']);


