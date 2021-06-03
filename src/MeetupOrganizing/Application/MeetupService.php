<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;
use Ramsey\Uuid\Uuid;

final class MeetupService
{
    private UserRepository $userRepository;

    private MeetupRepository $meetupRepository;

    private Clock $clock;

    public function __construct(
        UserRepository $userRepository,
        MeetupRepository $meetupRepository,
        Clock $clock
    ) {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
        $this->clock = $clock;
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        $user = $this->userRepository->getById($command->organizerId());

        $meetup = new Meetup(
            MeetupId::fromString(
                Uuid::uuid4()->toString()
            ),
            $user->userId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor(),
            $this->clock->currentTime()
        );

        $this->meetupRepository->save($meetup);

        return $meetup->getId();
    }
}
