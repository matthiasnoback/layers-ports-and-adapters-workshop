<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use Assert\Assert;
use DateTimeImmutable;
use InvalidArgumentException;

final class Meetup
{
    private array $events = [];

    private MeetupId $meetupId;

    private UserId $organizerId;

    private string $name;

    private string $description;

    private ScheduledDate $scheduledFor;

    private bool $wasCancelled = false;

    private function __construct(
        MeetupId $meetupId,
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor,
        bool $wasCancelled
    ) {
        $this->meetupId = $meetupId;
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduledFor = $scheduledFor;
        $this->wasCancelled = $wasCancelled;
    }

    public static function schedule(
            MeetupId $meetupId,
            UserId $organizerId,
            string $name,
            string $description,
            ScheduledDate $scheduledFor,
            DateTimeImmutable $currentTime
        ): self {
        Assert::that($name)->notEmpty('The name of the meetup should not be empty');
        Assert::that($description)->notEmpty('The description of the meetup should not be empty');
        if (!$scheduledFor->isInTheFuture($currentTime)) {
            throw new InvalidArgumentException('A new meetup should be in the future');
        }

        $meetup = new self(
            $meetupId,
            $organizerId,
            $name,
            $description,
            $scheduledFor,
            false
        );

        $meetup->events[] = new MeetupWasScheduled($meetup->meetupId);

        return $meetup;
    }

    /**
     * @param array<string,string> $record
     */
    public static function fromDatabaseRecord(array $record): self
    {
        return new self(
            MeetupId::fromString($record['meetupId']),
            UserId::fromInt((int)$record['organizerId']),
            $record['name'],
            $record['description'],
            ScheduledDate::fromString($record['scheduledFor']),
            (bool)$record['wasCancelled']
        );
    }

    public function releaseEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

    public function getData(): array
    {
        return [
            'meetupId' => $this->meetupId->asString(),
            'organizerId' => $this->organizerId->asInt(),
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor->asString(),
            'wasCancelled' => (int)$this->wasCancelled
        ];
    }

    public function getId(): MeetupId
    {
        return $this->meetupId;
    }

    public function cancel(): void
    {
        $this->wasCancelled = true;
    }

    public function organizerId(): UserId
    {
        return $this->organizerId;
    }
}
