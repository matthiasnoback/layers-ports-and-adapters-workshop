<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\ScheduleMeetup;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use Ramsey\Uuid\Uuid;

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
            $command->currentTime()
        );

        $this->meetupRepository->save($meetup);

        return $meetup->getId();
    }
}
