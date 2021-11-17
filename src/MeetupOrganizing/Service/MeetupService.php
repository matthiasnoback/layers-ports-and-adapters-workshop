<?php
declare(strict_types=1);

namespace MeetupOrganizing\Service;

use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Repository\MeetupRepository;

final class MeetupService
{
    private MeetupRepository $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function scheduleMeetup(
        int $organizerId,
        string $name,
        string $description,
        string $scheduledFor
    ): int {
        $meetup = new Meetup(
            $organizerId,
            $name,
            $description,
            $scheduledFor
        );

        return $this->meetupRepository->save($meetup);
    }
}
