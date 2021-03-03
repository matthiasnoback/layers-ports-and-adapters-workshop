<?php
declare(strict_types=1);

namespace MeetupOrganizing;

use Assert\Assert;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\MeetupRepository;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
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

    public function scheduleMeetup(
        int $organizerId,
        string $name,
        string $description,
        string $scheduledFor
    ): int {
        $user = $this->userRepository->getById(UserId::fromInt($organizerId));

        $meetup = new Meetup(
            $user->userId(),
            $name,
            $description,
            ScheduledDate::fromString(
                $scheduledFor
            )
        );

        $this->meetupRepository->save($meetup);

        Assert::that($meetup->getId())->integer();

        return $meetup->getId();
    }
}
