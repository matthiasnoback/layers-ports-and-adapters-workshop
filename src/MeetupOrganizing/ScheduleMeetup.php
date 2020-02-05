<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

final class ScheduleMeetup
{
    /**
     * @var int
     */
    public $organizerId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $scheduleFor;

    public function __construct(
        int $organizerId,
        string $name,
        string $description,
        string $scheduleFor
    ) {
        $this->organizerId = $organizerId;
        $this->name = $name;
        $this->description = $description;
        $this->scheduleFor = $scheduleFor;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('organizerId', new Assert\GreaterThan(['value' => 0]));
        $metadata->addPropertyConstraint('name', new Assert\NotBlank(['allowNull' => false, 'message' => 'Please fill in a name.'                                                                                                       ]));
        $metadata->addPropertyConstraint('description', new Assert\NotBlank(['allowNull' => false]));
        $metadata->addPropertyConstraint('scheduleFor', new Assert\DateTime(['format' => 'Y-m-d H:i']));
    }

    public function organizerId(): UserId
    {
        return UserId::fromInt($this->organizerId);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function scheduleFor(): ScheduledDate
    {
        return ScheduledDate::fromString($this->scheduleFor);
    }
}
