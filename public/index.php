<?php
declare(strict_types = 1);

use Interop\Container\ContainerInterface;
use MeetupOrganizing\Infrastructure\SchemaManager;
use MeetupOrganizing\Infrastructure\ServiceContainer;
use Zend\Expressive\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = new ServiceContainer(__DIR__ . '/../');

/** @var Application $app */
$app = $container[Application::class];

/** @var $schemaManager SchemaManager */
$schemaManager = $container[SchemaManager::class];
$schemaManager->updateSchema();

$app->run();
