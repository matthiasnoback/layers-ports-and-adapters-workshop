<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain;

trait EventRecordingCapabilities
{
    /**
     * @var array<object>
     */
    private array $events = [];

    private function recordThat(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array<object>
     */
    public function releaseEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }
}
