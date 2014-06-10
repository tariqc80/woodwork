<?php 
include('Woodwork/Core/__autoload.php');
use WoodWork\Core\Config;
use WoodWork\Core\Application;

// read configuration file
if (!file_exists('config.ini'))
{
	throw new Exception('config.ini not found!');
}

$config = new Config('config.ini');
$app = new Application( $config );

$app->run();