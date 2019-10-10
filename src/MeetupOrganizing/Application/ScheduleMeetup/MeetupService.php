<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\ScheduleMeetup;

use MeetupOrganizing\Application\ScheduleMeetup\ScheduleMeetup;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;

final class MeetupService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

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

        $this->meetupRepository->add($meetup);

        return $meetup->getId();
    }
}
