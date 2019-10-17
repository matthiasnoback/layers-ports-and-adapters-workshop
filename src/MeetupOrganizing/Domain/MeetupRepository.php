<?php

namespace MeetupOrganizing\Domain;

interface MeetupRepository
{
    public function add(Meetup $meetup): void;
}
