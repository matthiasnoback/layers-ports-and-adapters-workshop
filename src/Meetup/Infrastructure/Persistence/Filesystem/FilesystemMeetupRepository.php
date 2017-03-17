<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Repository\MeetupRepository;
use Meetup\Infrastructure\Persistence\Common\AbstractMeetupRepository;
use NaiveSerializer\Serializer;

final class FilesystemMeetupRepository implements MeetupRepository
{
    use AbstractMeetupRepository;

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
}
