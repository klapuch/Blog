<?php
declare(strict_types = 1);

// require __DIR__ . '/.maintenance.php';
$container = require __DIR__ . '/../app/bootstrap.php';
$container->getByType('Nette\Application\Application')->run();