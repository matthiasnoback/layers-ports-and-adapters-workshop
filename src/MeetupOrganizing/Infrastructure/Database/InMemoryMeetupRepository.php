<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use BadMethodCallException;
use DateTimeImmutable;
use MeetupOrganizing\Application\ListMeetups\ListMeetupsRepository;
use MeetupOrganizing\Application\ListMeetups\MeetupForList;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use Ramsey\Uuid\Uuid;

final class InMemoryMeetupRepository implements MeetupRepository, ListMeetupsRepository
{
    /**
     * @var array<string, Meetup> & Meetup[]
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

    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $meetupsForList = [];

        foreach ($this->meetups as $meetup) {
            if ($meetup->scheduledFor()->isInTheFuture($now)) {
                $meetupsForList[] = MeetupForList::fromDatabaseRecord($meetup->getData());
            }
        }

        return $meetupsForList;
    }

    /**
     * @inheritDoc
     */
    public function pastMeetups(DateTimeImmutable $now): array
    {
        throw new BadMethodCallException('Not implemented');
    }
}
