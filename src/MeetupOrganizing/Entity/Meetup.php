<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class Meetup
{
    private UserId $organizerId;
    private string $name;
    private string $description;
    private ScheduledDate $scheduledFor;
    private bool $wasCancelled = false;

    public function __construct(UserId $organizerId, string $name, string $description, ScheduledDate $scheduledFor)
    {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
    }

    /**
     * @internal Only to be used by the repository
     */
    public function asMySqlRecord(): array
    {
        return [
            'organizerId' => $this->organizerId->asInt(),
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor->asString(),
            'wasCancelled' => (int)$this->wasCancelled
        ];
    }
}
