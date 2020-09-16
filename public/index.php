<?php
declare(strict_types = 1);

use MeetupOrganizing\Infrastructure\ServiceContainer;
use Zend\Expressive\Application;

require __DIR__ . '/../vendor/autoload.php';

$container = new ServiceContainer(__DIR__ . '/../');

/** @var Application $app */
$app = $container[Application::class];

$app->run();
