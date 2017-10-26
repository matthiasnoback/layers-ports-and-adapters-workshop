<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use NaiveSerializer\Serializer;
use Ramsey\Uuid\Uuid;

final class FileBasedMeetupRepository implements MeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function add(Meetup $meetup): void
    {
        $meetups = $this->persistedMeetups();
        $meetups[] = $meetup;
        file_put_contents($this->filePath, Serializer::serialize($meetups));
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

    public function allMeetups(): array
    {
        return $this->persistedMeetups();
    }

    /**
     * @return Meetup[]
     */
    private function persistedMeetups(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $rawJson = file_get_contents($this->filePath);
        if (empty($rawJson)) {
            return [];
        }

        return Serializer::deserialize(Meetup::class . '[]', $rawJson);
    }

    public function deleteAll(): void
    {
        file_put_contents($this->filePath, '[]');
    }

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString((string)Uuid::uuid4());
    }
}
