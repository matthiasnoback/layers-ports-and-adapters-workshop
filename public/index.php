<?php

use Interop\Container\ContainerInterface;
use Zend\Expressive\Application;

require '../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../app/container.php';

/** @var Application $app */
$app = $container[Application::class];
$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();
$app->run();
