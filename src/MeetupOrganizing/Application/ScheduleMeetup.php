<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

final class ScheduleMeetup
{
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
        $formErrors = [];

        if (empty($this->name)) {
            $formErrors['name'][] = 'Provide a name';
        }

        if (empty($this->description)) {
            $formErrors['description'][] = 'Provide a description';
        }

        if (empty($this->scheduledFor)) {
            $formErrors['scheduledFor'][] = 'Provide a scheduled for date';
        }

        return $formErrors;
    }
}
