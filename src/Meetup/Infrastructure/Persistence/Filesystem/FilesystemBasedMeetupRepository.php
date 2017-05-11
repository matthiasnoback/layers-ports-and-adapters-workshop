<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Meetup;
use Meetup\Infrastructure\Persistence\Common\MeetupRepository as AbstractMeetupRepository;
use NaiveSerializer\Serializer;

final class FilesystemBasedMeetupRepository extends AbstractMeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return Meetup[]
     */
    protected function persistedMeetups(): array
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

    protected function persistMeetups(array $meetups): void
    {
        file_put_contents($this->filePath, Serializer::serialize($meetups));
    }
}
