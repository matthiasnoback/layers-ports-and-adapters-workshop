<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;

interface MeetupRepository
{
    /**
     * Watch out: saving for now means inserting!
     */
    public function save(Meetup $meetup): void;
}
