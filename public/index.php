<?php
declare(strict_types = 1);

use Interop\Container\ContainerInterface;
use MeetupOrganizing\SchemaManager;
use Zend\Expressive\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../app/container.php';

/** @var Application $app */
$app = $container[Application::class];

/** @var $schemaManager SchemaManager */
$schemaManager = $container[SchemaManager::class];
$schemaManager->updateSchema();

$app->run();
