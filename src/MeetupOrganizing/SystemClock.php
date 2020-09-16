<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use DateTimeImmutable;

final class SystemClock
{
    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
