<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Memory;

use BadMethodCallException;
use DateTimeImmutable;
use MeetupOrganizing\Application\ListMeetups\ListMeetupsRepository;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Application\ListMeetups\Meetup as MeetupForList;

final class InMemoryMeetupRepository implements MeetupRepository, ListMeetupsRepository
{
    /**
     * @var array&Meetup[]
     */
    private $meetups = [];

    public function add(Meetup $meetup): void
    {
        $id = count($this->meetups) + 1;
        $meetup->setId($id);

        $this->meetups[$meetup->getId()] = $meetup;
    }

    /**
     * @param DateTimeImmutable $now
     * @return array&MeetupForList[]
     */
    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $upcomingMeetups = [];

        foreach ($this->meetups as $meetup) {
            if ($meetup->scheduledFor()->isInTheFuture($now)) {
                $upcomingMeetups[] = MeetupForList::fromDatabaseRecord($meetup->getData());
            }
        }

        return $upcomingMeetups;
    }

    public function pastMeetups(DateTimeImmutable $now): array
    {
        throw new BadMethodCallException('Not implemented');
    }
}
