<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use Ramsey\Uuid\Uuid;

final class InMemoryMeetupRepository implements MeetupRepository
{
    /**
     * @var array<string, Meetup>
     */
    private $meetups = [];

    public function add(Meetup $meetup): void
    {
        $this->meetups[$meetup->getId()->asString()] = $meetup;
    }

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString(Uuid::uuid4()->toString());
    }
}
