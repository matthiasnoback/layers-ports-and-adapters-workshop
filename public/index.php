<?php
declare(strict_types = 1);

use Interop\Container\ContainerInterface;
use Zend\Expressive\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../app/container.php';

/** @var Application $app */
$app = $container[Application::class];
$app->run();
