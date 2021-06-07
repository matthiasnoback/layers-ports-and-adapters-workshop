<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;
use RuntimeException;

final class MeetupService
{
    private UserRepository $userRepository;

    private MeetupRepository $meetupRepository;

    private Clock $clock;

    private EventDispatcher $eventDispatcher;

    public function __construct(
        UserRepository $userRepository,
        MeetupRepository $meetupRepository,
        Clock $clock,
        EventDispatcher $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
        $this->clock = $clock;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        $user = $this->userRepository->getById($command->organizerId());

        $meetup = Meetup::schedule(
            $this->meetupRepository->nextIdentity(),
            $user->userId(),
            $command->name(),
            $command->description(),
            $command->scheduledFor(),
            $this->clock->currentTime()
        );

        $this->meetupRepository->save($meetup);

        $this->eventDispatcher->dispatchAll(
            $meetup->releaseEvents()
        );

        return $meetup->getId();
    }

    public function cancelMeetup(CancelMeetup $command): void
    {
        $meetup = $this->meetupRepository->getById($command->meetupId());

        if (!$command->loggedInUserId()->equals($meetup->organizerId())) {
            throw new RuntimeException('Only the organizer can cancel the meetup');
        }

        $meetup->cancel();

        $this->meetupRepository->update($meetup);

        $this->eventDispatcher->dispatchAll(
            $meetup->releaseEvents()
        );
    }
}
