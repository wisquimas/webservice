<?php
define('WWSICLO',__DIR__);
/*
** AUTOLOAD
*/
foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "*.php") as $filename)
{
	require_once $filename;
}
