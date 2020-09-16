<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use Assert\Assert;
use DateTimeImmutable;
use InvalidArgumentException;

final class Meetup
{
    private ?int $meetupId;

    private UserId $organizerId;

    private string $name;

    private string $description;

    private ScheduledDate $scheduledFor;

    private bool $wasCancelled = false;

    public function __construct(
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor,
        DateTimeImmutable $currentTime
    ) {
        Assert::that($name)->notEmpty('The name of the meetup should not be empty');
        Assert::that($description)->notEmpty('The description of the meetup should not be empty');
        if (!$scheduledFor->isInTheFuture($currentTime)) {
            throw new InvalidArgumentException('A new meetup should be in the future');
        }

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
     * @internal Only to be used by MeetupRepository
     */
    public function setId(int $meetupId): void
    {
        $this->meetupId = $meetupId;
    }

    public function getId(): ?int
    {
        return $this->meetupId;
    }
}
