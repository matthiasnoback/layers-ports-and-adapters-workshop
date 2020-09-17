<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use Assert\Assert;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;

final class MeetupService
{
    private UserRepository $userRepository;

    private MeetupRepository $meetupRepository;

    public function __construct(
        UserRepository $userRepository,
        MeetupRepository $meetupRepository
    ) {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
    }

    public function scheduleMeetup(ScheduleMeetup $command): int
    {
        $user = $this->userRepository->getById($command->organizerId());

        $meetup = new Meetup(
            $user->userId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor(),
            $command->currentTime()
        );

        $this->meetupRepository->save($meetup);

        Assert::that($meetup->getId())->integer();

        return $meetup->getId();
    }
}
