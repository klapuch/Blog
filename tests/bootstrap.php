<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

Testbench\Bootstrap::setup(
    __DIR__ . '/temp',
    function (Nette\Configurator $configuration) {
        $configuration->addParameters(['testDir' => __DIR__]);
        $configuration->addConfig(__DIR__ . '/test.neon');
        $configuration->addConfig(__DIR__ . '/testbench.neon');
    }
);

$configuration = new Nette\Configurator;
$configuration->setDebugMode(false);
$configuration->setTempDirectory(__DIR__ . '/temp');
$configuration->createRobotLoader()
    ->addDirectory(__DIR__ . '/TestCase')
    ->register();
$configuration->addConfig(__DIR__ . '/test.neon');
return $configuration->createContainer();