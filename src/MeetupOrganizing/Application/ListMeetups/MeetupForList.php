<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\ListMeetups;

final class MeetupForList
{
    private int $meetupId;

    private string $name;

    private string $scheduledFor;

    private function __construct()
    {
    }

    public static function fromDatabaseRecord(array $record): self
    {
        $meetup = new self();

        $meetup->meetupId = (int)$record['meetupId'];
        $meetup->name = $record['name'];
        $meetup->scheduledFor = $record['scheduledFor'];

        return $meetup;
    }

    public function meetupId(): int
    {
        return $this->meetupId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function scheduledFor(): string
    {
        return $this->scheduledFor;
    }
}
