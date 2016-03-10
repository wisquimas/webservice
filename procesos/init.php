<?php
header('Content-Type: application/json');
define('DIRECTORIO_BASE',__DIR__);
define('HOME_URL',"http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");

require_once('../../wp-load.php');

/*
**AUTOLOAD
*/
/*AUTOLOAD FILES*/
foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "*.php") as $filename)
{
    require_once $filename;
};
/*CUSTOM DATA*/
foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "customService" . DIRECTORY_SEPARATOR . "*.php") as $filename)
{
    require_once $filename;
};


$system = new Wisquimas\Core( __DIR__ );

$system->ControlarRequest();
