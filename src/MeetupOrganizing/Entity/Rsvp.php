<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

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

    public function __construct(
        UuidInterface $rsvpId,
        int $meetupId,
        UserId $userId
    ) {
        $this->rsvpId = $rsvpId;
        $this->meetupId = $meetupId;
        $this->userId = $userId;
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
