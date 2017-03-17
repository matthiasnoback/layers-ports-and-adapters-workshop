<?php
declare(strict_types = 1);

namespace Meetup\Application;

use ConvenientImmutability\Immutable;

final class ScheduleMeetup
{
    use Immutable;

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

    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'][] = 'Provide a name';
        }

        if (!isset($data['description']) || empty($data['description'])) {
            $errors['description'][] = 'Provide a description';
        }

        if (!isset($data['scheduledFor']) || empty($data['scheduledFor'])) {
            $errors['scheduledFor'][] = 'Provide a scheduled for date';
        }

        return $errors;
    }
}
