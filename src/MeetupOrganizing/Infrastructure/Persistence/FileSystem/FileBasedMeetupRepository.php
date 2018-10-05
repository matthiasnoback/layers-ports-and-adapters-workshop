<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Persistence\FileSystem;

use MeetupOrganizing\Domain\Model\Meetup;
use MeetupOrganizing\Infrastructure\Persistence\Common\AbstractMeetupRepository;
use NaiveSerializer\Serializer;

final class FileBasedMeetupRepository extends AbstractMeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function allMeetups(): array
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

    /**
     * @param array|Meetup[] $meetups
     * @return void
     */
    protected function persistMeetups(array $meetups): void
    {
        file_put_contents($this->filePath, Serializer::serialize($meetups));
    }

    public function deleteAll(): void
    {
        file_put_contents($this->filePath, '');
    }
}
