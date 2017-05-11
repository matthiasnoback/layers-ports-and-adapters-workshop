<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\Common;

use Meetup\Domain\Meetup;
use Meetup\Domain\MeetupId;
use Meetup\Domain\MeetupRepository as MeetupRepositoryInterface;
use Ramsey\Uuid\Uuid;

abstract class MeetupRepository implements MeetupRepositoryInterface
{
    public function add(Meetup $meetup): void
    {
        $meetups = $this->persistedMeetups();
        $meetups[] = $meetup;
        $this->persistMeetups($meetups);

    }

    /**
     * @param array $meetups|Meetup[]
     * @return void
     */
    abstract protected function persistMeetups(array $meetups): void;

    /**
     * @return array|Meetup[]
     */
    abstract protected function persistedMeetups(): array;

    public function byId(MeetupId $id): Meetup
    {
        foreach ($this->persistedMeetups() as $meetup) {
            if ($meetup->id()->equals($id)) {
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

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString((string)Uuid::uuid4());
    }
}
