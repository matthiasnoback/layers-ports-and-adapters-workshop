<?php
declare(strict_types=1);

namespace MeetupOrganizing\Service;

use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;
use MeetupOrganizing\Repository\MeetupRepository;

final class MeetupService
{
    private MeetupRepository $meetupRepository;
    private UserRepository $userRepository;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository
    )
    {
        $this->meetupRepository = $meetupRepository;
        $this->userRepository = $userRepository;
    }

    public function scheduleMeetup(
        int $organizerId,
        string $name,
        string $description,
        string $scheduledFor
    ): int {
        $this->assertOrganizerExists($organizerId);

        $meetup = new Meetup(
            UserId::fromInt($organizerId),
            $name,
            $description,
            ScheduledDate::fromString($scheduledFor)
        );

        return $this->meetupRepository->save($meetup);
    }

    private function assertOrganizerExists(int $organizerId): void
    {
        $this->userRepository->getById(UserId::fromInt($organizerId));
    }
}
