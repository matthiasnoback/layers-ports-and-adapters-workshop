<?php
namespace Meetup\Domain\Repository;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;

    public function byId(MeetupId $id): Meetup;

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now): array;

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array;

    public function nextIdentity(): MeetupId;
}
