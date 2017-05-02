<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\InMemory;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Ramsey\Uuid\Uuid;

final class InMemoryMeetupRepository implements MeetupRepository
{
    private $persistedMeetups = [];

    public function add(Meetup $meetup): void
    {
        $this->persistedMeetups[] = $meetup;
    }

    public function byId(MeetupId $meetupId): Meetup
    {
        foreach ($this->persistedMeetups as $meetup) {
            if ($meetup->meetupId()->equals($meetupId)) {
                return $meetup;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now): array
    {
        return array_values(array_filter($this->persistedMeetups, function (Meetup $meetup) use ($now) {
            return $meetup->isUpcoming($now);
        }));
    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array
    {
        return array_values(array_filter($this->persistedMeetups, function (Meetup $meetup) use ($now) {
            return !$meetup->isUpcoming($now);
        }));
    }

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString((string)Uuid::uuid4());
    }
}
