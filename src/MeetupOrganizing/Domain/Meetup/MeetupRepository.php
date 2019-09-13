<?php

namespace MeetupOrganizing\Domain\Meetup;

use MeetupOrganizing\Domain\Meetup\Meetup;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;
}
