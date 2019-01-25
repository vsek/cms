<?php
use Nextras\Migrations\Bridges;
use Nextras\Migrations\Controllers;
use Nextras\Migrations\Drivers;
use Nextras\Migrations\Extensions;

require __DIR__ . '/../vendor/autoload.php';

$configDir = __DIR__ . '/../app/config/';

if(file_exists($configDir . 'config.local.neon')){
    $config = \Nette\Neon\Neon::decode(file_get_contents($configDir . 'config.local.neon'));
}else{
    $config = \Nette\Neon\Neon::decode(file_get_contents($configDir . 'config.neon'));
}
$database = $config['database'];
$conn = new Nette\Database\Connection($database['dsn'],$database['user'],$database['password']);

$dbal = new Bridges\NetteDatabase\NetteAdapter($conn);

$driver = new Drivers\MySqlDriver($dbal);

$controller = new Controllers\HttpController($driver);

$baseDir = __DIR__;
$controller->addGroup('structures', "$baseDir/structures");
$controller->addGroup('basic-data', "$baseDir/basic-data", ['structures']);
$controller->addGroup('dummy-data', "$baseDir/dummy-data", ['basic-data']);
$controller->addExtension('sql', new Extensions\SqlHandler($driver));

$controller->run();