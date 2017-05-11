<?php
declare(strict_types=1);

namespace Meetup\Application;

final class ScheduleMeetup
{
    const ID_SHOULD_NOT_BE_EMPTY = 'id.should_not_be_empty';
    const NAME_SHOULD_NOT_BE_EMPTY = 'name.should_not_be_empty';
    const DESCRIPTION_SHOULD_NOT_BE_EMPTY = 'description.should_not_be_empty';
    const SCHEDULED_FOR_SHOULD_NOT_BE_EMPTY = 'scheduled_for.should_not_be_empty';
    const INVALID_SCHEDULED_FOR_DATE = 'scheduled_for.invalid';

    /**
     * @var string
     */
    public $id;

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
    public $scheduledFor;

    public function validate(): array
    {
        $validationErrors = [];

        if (empty($this->id)) {
            $validationErrors['id'][] = self::ID_SHOULD_NOT_BE_EMPTY;
        }
        if (empty($this->name)) {
            $validationErrors['name'][] = self::NAME_SHOULD_NOT_BE_EMPTY;
        }
        if (empty($this->description)) {
            $validationErrors['description'][] = self::NAME_SHOULD_NOT_BE_EMPTY;
        }
        if (empty($this->scheduledFor)) {
            $validationErrors['scheduledFor'][] = self::SCHEDULED_FOR_SHOULD_NOT_BE_EMPTY;
        } else {
            try {
                new \DateTimeImmutable($this->scheduledFor);
            } catch (\Throwable $fault) {
                $validationErrors['scheduledFor'][] = self::INVALID_SCHEDULED_FOR_DATE;
            }
        }

        return $validationErrors;
    }
}
