<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\Clock;

final class SystemClock implements Clock
{
    public function currentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
