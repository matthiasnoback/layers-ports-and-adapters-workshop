<?php
declare(strict_types=1);

namespace MeetupOrganizing\Service;

use MeetupOrganizing\Command\ScheduleMeetup;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\UserId;
use MeetupOrganizing\Entity\UserRepository;
use MeetupOrganizing\Repository\MeetupRepository;

final class MeetupService
{
    private MeetupRepository $meetupRepository;
    private UserRepository $userRepository;
    private Clock $clock;

    public function __construct(
        MeetupRepository $meetupRepository,
        UserRepository $userRepository,
        Clock $clock
    )
    {
        $this->meetupRepository = $meetupRepository;
        $this->userRepository = $userRepository;
        $this->clock = $clock;
    }

    public function scheduleMeetup(ScheduleMeetup $command): int
    {
        $this->assertOrganizerExists($command->organizerId());

        $meetup = Meetup::schedule(
            $command->organizerId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor(),
            $this->clock->currentTime()
        );

        return $this->meetupRepository->save($meetup);
    }

    private function assertOrganizerExists(UserId $organizerId): void
    {
        $this->userRepository->getById($organizerId);
    }
}
