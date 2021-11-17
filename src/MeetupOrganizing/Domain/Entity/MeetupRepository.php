<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Entity;

interface MeetupRepository
{
    public function save(Meetup $meetup): int;
}
