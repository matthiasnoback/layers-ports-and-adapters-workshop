<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

final class Meetup
{
    private int $organizerId;
    private string $name;
    private string $description;
    private string $scheduledFor;
    private bool $wasCancelled = false;

    public function __construct(int $organizerId, string $name, string $description, string $scheduledFor)
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
            'organizerId' => $this->organizerId,
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor,
            'wasCancelled' => (int)$this->wasCancelled
        ];
    }
}
