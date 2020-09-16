<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use DateTimeImmutable;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;

final class ScheduleMeetup
{
    private int $organizerId;

    private string $name;

    private string $description;

    private string $scheduledFor;

    private DateTimeImmutable $currentTime;

    public function __construct(
        int $organizerId,
        string $name,
        string $description,
        string $scheduledFor,
        DateTimeImmutable $currentTime
    ) {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
        $this->currentTime = $currentTime;
    }

    public function organizerId(): UserId
    {
        return UserId::fromInt($this->organizerId);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function scheduledFor(): ScheduledDate
    {
        return ScheduledDate::fromString($this->scheduledFor);
    }

    public function currentTime(): DateTimeImmutable
    {
        return $this->currentTime;
    }
}
