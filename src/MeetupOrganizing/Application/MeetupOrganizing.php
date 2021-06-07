<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Meetup;
use MeetupOrganizing\Domain\MeetupId;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;
use RuntimeException;

final class MeetupOrganizing implements MeetupOrganizingInterface
{
    private UserRepository $userRepository;

    private MeetupRepository $meetupRepository;

    private Clock $clock;

    private EventDispatcher $eventDispatcher;
    private ListMeetupsRepository $listMeetupsRepository;

    public function __construct(
        UserRepository $userRepository,
        MeetupRepository $meetupRepository,
        Clock $clock,
        EventDispatcher $eventDispatcher,
        ListMeetupsRepository $listMeetupsRepository
    ) {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
        $this->clock = $clock;
        $this->eventDispatcher = $eventDispatcher;
        $this->listMeetupsRepository = $listMeetupsRepository;
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

    public function listUpcomingMeetups(): array
    {
        return $this->listMeetupsRepository->upcomingMeetups($this->clock->currentTime());
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

    public function listPastMeetups(): array
    {
        return $this->listMeetupsRepository->pastMeetups($this->clock->currentTime());
    }
}
