<?php

namespace Meetup\Domain\Model;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;

    public function byId(int $id): Meetup;

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
}