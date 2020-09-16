<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

use Ramsey\Uuid\UuidInterface;

final class UserHasRsvpd
{
    private string $meetupId;
    private UserId $userId;
    private UuidInterface $rsvpId;

    public function __construct(string $meetupId, UserId $userId, UuidInterface $rsvpId)
    {
        $this->meetupId = $meetupId;
        $this->userId = $userId;
        $this->rsvpId = $rsvpId;
    }

    public function meetupId(): string
    {
        return $this->meetupId;
    }

    public function rsvpId(): UuidInterface
    {
        return $this->rsvpId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
