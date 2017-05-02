<?php
declare(strict_types = 1);

use Interop\Container\ContainerInterface;
use Meetup\Infrastructure\MeetupApplication;
use Zend\Expressive\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $meetupApplication */
$meetupApplication = new MeetupApplication();

/** @var Application $app */
$app = $meetupApplication[Application::class];
$app->run();
