<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\InMemory;

use Meetup\Infrastructure\Persistence\Common\AbstractMeetupRepository;

final class InMemoryMeetupRepository extends AbstractMeetupRepository
{
    private $persistedMeetups = [];

    protected function persistMeetups(array $meetups): void
    {
        $this->persistedMeetups = $meetups;
    }

    public function allMeetups(): array
    {
        return $this->persistedMeetups;
    }

    public function deleteAll(): void
    {
        $this->persistedMeetups = [];
    }
}
