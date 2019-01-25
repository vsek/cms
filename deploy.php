<?php

require __DIR__ . '/vendor/autoload.php';

# Configurator
$configurator = new Nette\Configurator;
$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/log');
$configurator->setTempDirectory(__DIR__ . '/temp');

$configurator->createRobotLoader()
    ->addDirectory(__DIR__ . '/app')
    ->addDirectory(__DIR__ . '/vendor')
    ->register();
# Configs
$configurator->addConfig(__DIR__ . '/app/config/config.neon');
if(file_exists(dirname(__FILE__) . '/app/config/config.local.neon')){
    $configurator->addConfig(__DIR__ . '/app/config/config.local.neon');
}
# Create DI Container
$container = $configurator->createContainer();

# Create Deploy Manager
$dm = $container->getByType('App\Component\Deploy\Manager');
$dm->deploy();