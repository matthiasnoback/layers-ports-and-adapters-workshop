<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Rsvp
{
    /**
     * @var UuidInterface
     */
    private $rsvpId;

    /**
     * @var int
     */
    private $meetupId;

    /**
     * @var UserId
     */
    private $userId;

    private function __construct(
        UuidInterface $rsvpId,
        int $meetupId,
        UserId $userId
    ) {
        $this->rsvpId = $rsvpId;
        $this->meetupId = $meetupId;
        $this->userId = $userId;
    }

    public static function create(int $meetupId, UserId $userId): Rsvp
    {
        return new self(Uuid::uuid4(), $meetupId, $userId);
    }

    public static function fromDatabaseRecord(array $record): Rsvp
    {
        return new self(
            Uuid::fromString($record['rsvpId']),
            (int)$record['meetupId'],
            UserId::fromInt((int)$record['userId'])
        );
    }

    public function rsvpId(): UuidInterface
    {
        return $this->rsvpId;
    }

    public function meetupId(): int
    {
        return $this->meetupId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
