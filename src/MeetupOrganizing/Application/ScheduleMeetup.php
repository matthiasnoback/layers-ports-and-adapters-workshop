<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Entity\ScheduledDate;
use MeetupOrganizing\Domain\Entity\UserId;

final class ScheduleMeetup
{
    private int $organizerId;
    private string $name;
    private string $description;
    private string $scheduledFor;

    public function __construct(
        int $organizerId,
        string $name,
        string $description,
        string $scheduledFor
    ) {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
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
}
