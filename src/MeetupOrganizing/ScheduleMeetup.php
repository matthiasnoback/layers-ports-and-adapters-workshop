<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;

final class ScheduleMeetup
{
    /**
     * @var int
     */
    private $organizerId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $scheduledFor;

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
