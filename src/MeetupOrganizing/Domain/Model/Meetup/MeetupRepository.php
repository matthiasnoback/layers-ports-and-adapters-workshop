<?php

namespace MeetupOrganizing\Domain\Model\Meetup;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;
}
