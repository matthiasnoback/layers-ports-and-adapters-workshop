<?php
declare(strict_types=1);

namespace MeetupOrganizing\Repository;

final class MeetupForList
{
    private int $meetupId;
    private string $name;
    private string $scheduledFor;

    private function __construct(
        int $meetupId,
        string $name,
        string $scheduledFor
    ) {
        $this->meetupId = $meetupId;
        $this->name = $name;
        $this->scheduledFor = $scheduledFor;
    }

    public static function fromMySqlRecord(array $record): self
    {
        return new self(
            (int)$record['meetupId'],
            $record['name'],
            $record['scheduledFor'],
        );
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
