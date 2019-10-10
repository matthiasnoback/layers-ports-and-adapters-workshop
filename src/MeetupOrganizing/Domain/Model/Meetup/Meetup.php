<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use Assert\Assertion;
use MeetupOrganizing\Domain\Model\Meetup\ScheduledDate;
use MeetupOrganizing\Domain\Model\User\UserId;

final class Meetup
{
    /**
     * @var int
     */
    private $meetupId;

    /**
     * @var UserId
     */
    private $organizerId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ScheduledDate
     */
    private $scheduledFor;

    public function __construct(
        UserId $organizerId,
        string $name,
        string $description,
        ScheduledDate $scheduledFor
    ) {
        $this->organizerId = $organizerId;

        Assertion::notEmpty($name, '$name should not be empty');
        $this->name = $name;

        Assertion::notEmpty($description, '$description should not be empty');
        $this->description = $description;

        $this->scheduledFor = $scheduledFor;
    }

    public function getData(): array
    {
        return [
            'organizerId' => $this->organizerId->asInt(),
            'name' => $this->name,
            'description' => $this->description,
            'scheduledFor' => $this->scheduledFor->asString()
        ];
    }

    /**
     * @param int $meetupId
     * @internal Only to be used by MeetupRepository
     */
    public function setId(int $meetupId): void
    {
        $this->meetupId = $meetupId;
    }

    public function getId(): int
    {
        return $this->meetupId;
    }
}
