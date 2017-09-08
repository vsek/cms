<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode('109.164.19.159'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
        ->addDirectory(dirname(__FILE__) . '/../libs')
        ->addDirectory(dirname(__FILE__) . '/../vendor/vsek')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
if(file_exists(__DIR__ . '/config/config.local.neon')) {
    $configurator->addConfig(__DIR__ . '/config/config.local.neon');
}

$container = $configurator->createContainer();

return $container;
