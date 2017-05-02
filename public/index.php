<?php
declare(strict_types = 1);

use Meetup\Infrastructure\Common\MeetupApplicationContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new MeetupApplicationContainer(__DIR__ . '/../');

$container->getWebApplication()->run();
