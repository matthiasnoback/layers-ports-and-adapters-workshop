<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Infrastructure\Persistence\Common\AbstractMeetupRepository;
use NaiveSerializer\Serializer;
use Ramsey\Uuid\Uuid;

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

    /**
     * @return Meetup[]
     */
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
