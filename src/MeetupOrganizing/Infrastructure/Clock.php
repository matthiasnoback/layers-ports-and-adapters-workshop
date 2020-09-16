<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use DateTimeImmutable;

final class Clock
{
    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
