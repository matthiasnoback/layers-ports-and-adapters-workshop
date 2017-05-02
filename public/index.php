<?php
declare(strict_types = 1);

use Meetup\Infrastructure\MeetupApplicationContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new MeetupApplicationContainer();

$container->getWebApplication()->run();
