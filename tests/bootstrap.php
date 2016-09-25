<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Prague');

Testbench\Bootstrap::setup(
    __DIR__ . '/_temp',
    function (Nette\Configurator $configuration) {
        $configuration->addParameters(['testDir' => __DIR__]);
        $configuration->addConfig(__DIR__ . '/test.neon');
        $configuration->addConfig(__DIR__ . '/testbench.neon');
    }
);
