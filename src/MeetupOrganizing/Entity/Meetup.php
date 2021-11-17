<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Assert\Assertion;
use DateTimeImmutable;

final class Meetup
{
    private UserId $organizerId;

    private string $name;
    private string $description;
    private ScheduledDate $scheduledFor;
    private bool $wasCancelled = false;

    private function __construct()
    {
    }

    public static function schedule(UserId $organizerId, string $name, string $description, ScheduledDate $scheduledFor, DateTimeImmutable $now): self
    {
        $self = new self();
        // pre-conditions
        Assertion::notBlank($name, 'Name should not be empty');
        Assertion::notBlank($description, 'Description should not be empty');
        Assertion::true(
            $scheduledFor->isInTheFuture($now),
            'Scheduled date should be in the future'
        );

        $self->organizerId = $organizerId;
        $self->name = $name;
        $self->description = $description;
        $self->scheduledFor = $scheduledFor;

        return $self;
    }

    public function reschedule(ScheduledDate $newDate): void
    {
        $this->scheduledFor = $newDate;
    }

    public function addVideoRecordingUrl(string $url): void
    {
        //
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

    public static function fromMySqlRecord(array $record): self
    {
        $self = new self();

        $self->name = $record['name'];
        $self->scheduledFor = ScheduledDate::fromString($record['scheduledFor']);

        return $self;
    }
}
