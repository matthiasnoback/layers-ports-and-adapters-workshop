<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class Meetup
{
    /**
     * @var int
     */
    private $meetupId;

    /**
     * @var UserId
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
     * @var ScheduledDate
     */
    private $scheduledFor;

    /**
     * @var bool
     */
    private $wasCancelled = false;

    public function __construct(
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor
    ) {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
    }

    public function getData(): array
    {
        return [
            'organizerId' => $this->organizerId->asInt(),
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor->asString(),
            'wasCancelled' => (int)$this->wasCancelled
        ];
    }

    /**
     * @param int $meetupId
     * @internal Only to be used by MeetupRepository
     */
    public function setId(int $meetupId): void
    {
        $this->meetupId = $meetupId;
    }

    public function getId(): int
    {
        return $this->meetupId;
    }
}
