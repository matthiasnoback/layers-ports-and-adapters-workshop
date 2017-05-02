<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\Common;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Ramsey\Uuid\Uuid;

abstract class AbstractMeetupRepository implements MeetupRepository
{
    /**
     * @param array|Meetup[] $meetups
     * @return void
     */
    abstract protected function persistMeetups(array $meetups): void;

    /**
     * @return array|Meetup[]
     */
    abstract protected function persistedMeetups(): array;

    public function add(Meetup $meetup): void
    {
        $meetups = $this->persistedMeetups();
        $meetups[] = $meetup;
        $this->persistMeetups($meetups);
    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function upcomingMeetups(\DateTimeImmutable $now): array
    {
        return array_values(array_filter($this->persistedMeetups(), function (Meetup $meetup) use ($now) {
            return $meetup->isUpcoming($now);
        }));
    }

    /**
     * @param \DateTimeImmutable $now
     * @return Meetup[]
     */
    public function pastMeetups(\DateTimeImmutable $now): array
    {
        return array_values(array_filter($this->persistedMeetups(), function (Meetup $meetup) use ($now) {
            return !$meetup->isUpcoming($now);
        }));
    }

    public function byId(MeetupId $meetupId): Meetup
    {
        foreach ($this->persistedMeetups() as $meetup) {
            if ($meetup->meetupId()->equals($meetupId)) {
                return $meetup;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString((string)Uuid::uuid4());
    }
}
