<?php
declare(strict_types=1);

namespace Meetup\Application;

use ConvenientImmutability\Immutable;

final class ScheduleMeetup
{
    use Immutable;

    /**
     * @var string
     */
    public  $id;

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
}
