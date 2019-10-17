<?php
declare(strict_types=1);

namespace Test\Acceptance;

use BadMethodCallException;
use DateTimeImmutable;
use MeetupOrganizing\Application\ListMeetupsRepository;
use MeetupOrganizing\Application\MeetupForList;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\ScheduledDate;

final class InMemoryMeetupRepository implements MeetupRepository, ListMeetupsRepository
{
    /**
     * @var array&Meetup[]
     */
    private $meetups = [];

    public function __construct()
    {
    }

    public function add(Meetup $meetup): void
    {
        $meetup->setId(1);
        $this->meetups[] = $meetup;
    }

    public function upcomingMeetups(DateTimeImmutable $now): array
    {
        $matchingEntities = array_filter($this->meetups, function (Meetup $meetup) use ($now) {
            return $meetup->scheduledDate()->asString()
                > ScheduledDate::fromDateTime(new \DateTimeImmutable())->asString();
        });

        return array_map(function (Meetup $meetup) {
            return MeetupForList::fromDatabaseRecord(
                $meetup->getData()
            );
        }, $matchingEntities);
    }

    public function pastMeetups(DateTimeImmutable $now): array
    {
        throw new BadMethodCallException('Not implemented');
    }
}
