<?php

namespace MeetupOrganizing\Domain\Model\Meetup;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;
}
