<?php

namespace Meetup\Entity;

class MeetupRepository
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function add(Meetup $meetup)
    {
        $meetups = $this->persistedMeetups();
        $meetups[] = $meetup;
        file_put_contents($this->filePath, serialize($meetups));
    }

    public function byId(MeetupId $meetupId)
    {
        foreach ($this->persistedMeetups() as $meetup) {
            if ($meetup->id()->equals($meetupId)) {
                return $meetup;
            }
        }

        throw new \RuntimeException('Meetup not found');
    }

    public function upcomingMeetups(\DateTimeImmutable $now)
    {
        return array_values(array_filter($this->persistedMeetups(), function (Meetup $meetup) use ($now) {
            return $meetup->isUpcoming($now);
        }));
    }

    public function pastMeetups(\DateTimeImmutable $now)
    {
        return array_values(array_filter($this->persistedMeetups(), function (Meetup $meetup) use ($now) {
            return !$meetup->isUpcoming($now);
        }));
    }

    /**
     * @return Meetup[]
     */
    private function persistedMeetups()
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        if (empty(file_get_contents($this->filePath))) {
            return [];
        }

        return unserialize(file_get_contents($this->filePath));
    }
}
