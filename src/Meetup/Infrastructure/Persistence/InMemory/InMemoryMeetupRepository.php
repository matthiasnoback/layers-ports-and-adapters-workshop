<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Persistence\InMemory;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Repository\MeetupRepository;
use Meetup\Infrastructure\Persistence\Common\AbstractMeetupRepository;

final class InMemoryMeetupRepository implements MeetupRepository
{
    use AbstractMeetupRepository;

    private $meetups = [];

    public function add(Meetup $meetup): void
    {
        $this->meetups[(string)$meetup->id()] = $meetup;
    }

    protected function persistedMeetups(): array
    {
        return $this->meetups;
    }
}
