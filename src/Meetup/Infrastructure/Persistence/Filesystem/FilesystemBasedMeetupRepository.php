<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Meetup;
use Meetup\Domain\MeetupId;
use Meetup\Domain\MeetupRepository;
use NaiveSerializer\Serializer;
use Ramsey\Uuid\Uuid;

final class FilesystemBasedMeetupRepository implements MeetupRepository
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

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString((string)Uuid::uuid4());
    }
}
