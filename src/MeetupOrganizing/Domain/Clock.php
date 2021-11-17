<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

interface Clock
{
    public function currentTime(): \DateTimeImmutable;
}
