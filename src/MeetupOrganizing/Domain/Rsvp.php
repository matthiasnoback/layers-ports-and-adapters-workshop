<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Rsvp
{
    private UuidInterface $rsvpId;

    private string $meetupId;

    private UserId $userId;

    private function __construct(
        UuidInterface $rsvpId,
        string $meetupId,
        UserId $userId
    ) {
        $this->rsvpId = $rsvpId;
        $this->meetupId = $meetupId;
        $this->userId = $userId;
    }

    public static function create(string $meetupId, UserId $userId): Rsvp
    {
        return new self(Uuid::uuid4(), $meetupId, $userId);
    }

    public static function fromDatabaseRecord(array $record): Rsvp
    {
        return new self(
            Uuid::fromString($record['rsvpId']),
            $record['meetupId'],
            UserId::fromInt((int)$record['userId'])
        );
    }

    public function rsvpId(): UuidInterface
    {
        return $this->rsvpId;
    }

    public function meetupId(): string
    {
        return $this->meetupId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
