<?php 

error_reporting(E_ALL);
ini_set('display_errors', true);

include('vendor/autoload.php');
use Woodwork\Core\Config;
use Woodwork\Core\Application;

// read configuration file

$config = new Config('config.ini');
$app = new Application( $config );

$app->run();