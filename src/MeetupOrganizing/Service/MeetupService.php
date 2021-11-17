<?php
declare(strict_types=1);

namespace MeetupOrganizing\Service;

use MeetupOrganizing\Command\ScheduleMeetup;
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

    public function scheduleMeetup(ScheduleMeetup $command): int
    {
        $this->assertOrganizerExists($command->organizerId());

        $meetup = new Meetup(
            $command->organizerId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor()
        );

        return $this->meetupRepository->save($meetup);
    }

    private function assertOrganizerExists(UserId $organizerId): void
    {
        $this->userRepository->getById($organizerId);
    }
}
