<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use Assert\Assert;
use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Infrastructure\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;
use MeetupOrganizing\Infrastructure\SystemClock;

final class MeetupService
{
    private UserRepository $userRepository;

    private MeetupRepository $meetupRepository;

    private SystemClock $clock;

    public function __construct(
        UserRepository $userRepository,
        MeetupRepository $meetupRepository,
        SystemClock $clock
    ) {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
        $this->clock = $clock;
    }

    public function scheduleMeetup(ScheduleMeetup $command): int
    {
        $user = $this->userRepository->getById($command->organizerId());

        $meetup = new Meetup(
            $user->userId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor(),
            $this->clock->currentTime()
        );

        $this->meetupRepository->save($meetup);

        Assert::that($meetup->getId())->integer();

        return $meetup->getId();
    }
}
