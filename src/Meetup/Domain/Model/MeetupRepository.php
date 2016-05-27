<?php

namespace Meetup\Domain\Model;

interface MeetupRepository
{
    /**
     * @param Meetup $meetup
     * @return void
     */
    public function add(Meetup $meetup);

    /**
     * @param MeetupId $meetupId
     * @return Meetup
     */
    public function byId(MeetupId $meetupId);

    /**
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now);

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now);
}
