<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use Assert\Assert;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\UserRepository;

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
            $command->scheduledFor()
        );

        $this->meetupRepository->save($meetup);

        Assert::that($meetup->getId())->integer();

        return $meetup->getId();
    }
}
