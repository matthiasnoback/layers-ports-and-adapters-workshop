<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use DateTimeImmutable;
use MeetupOrganizing\Application\Clock;

final class SystemClock implements Clock
{
    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
