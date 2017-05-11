<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\InMemory;

use Meetup\Infrastructure\Persistence\Common\MeetupRepository;

final class InMemoryMeetupRepository extends MeetupRepository
{
    private $meetups = [];

    protected function persistMeetups(array $meetups): void
    {
        $this->meetups = $meetups;
    }

    protected function persistedMeetups(): array
    {
        return $this->meetups;
    }
}
