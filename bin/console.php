<?php
/** @var \Nette\DI\Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';
$console = $container->getByType(\Symfony\Component\Console\Application::class);
exit($console->run());