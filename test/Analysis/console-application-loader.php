<?php
declare(strict_types=1);

use MeetupOrganizing\Command\ConsoleApplication;
use MeetupOrganizing\ServiceContainer;

return new ConsoleApplication(
    new ServiceContainer(__DIR__ . '/..')
);
