<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use DateTimeImmutable;

interface Clock
{
    public function currentTime(): DateTimeImmutable;
}
