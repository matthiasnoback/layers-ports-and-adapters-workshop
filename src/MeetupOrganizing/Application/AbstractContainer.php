<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\MeetupWasCancelled;
use MeetupOrganizing\Domain\RsvpRepository;
use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Domain\UserRepository;

abstract class AbstractContainer implements Container
{
    private ?ConfigurableEventDispatcher $eventDispatcher = null;

    public function meetupOrganizing(): MeetupOrganizingInterface
    {
        return new MeetupOrganizing(
            $this->userRepository(),
            $this->meetupRepository(),
            $this->clock(),
            $this->eventDispatcher(),
            $this->listMeetupsRepository(),
            $this->rsvpRepository()
        );
    }

    abstract protected function userRepository(): UserRepository;

    abstract protected function meetupRepository(): MeetupRepository;

    abstract protected function rsvpRepository(): RsvpRepository;

    abstract protected function clock(): Clock;

    abstract protected function listMeetupsRepository(): ListMeetupsRepository;

    private function eventDispatcher(): EventDispatcher
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new ConfigurableEventDispatcher();

            $this->registerCoreListeners($this->eventDispatcher);
            assert($this->eventDispatcher instanceof ConfigurableEventDispatcher);

            $this->registerInfrastructureListeners($this->eventDispatcher);
            assert($this->eventDispatcher instanceof ConfigurableEventDispatcher);
        }

        return $this->eventDispatcher;
    }

    private function registerCoreListeners(ConfigurableEventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->registerSpecificListener(
            UserHasRsvpd::class,
            [$this->sendEmailListener(), 'whenUserHasRsvpd']
        );

        $eventDispatcher->registerSpecificListener(
            MeetupWasCancelled::class,
            [$this->sendEmailListener(), 'whenMeetupWasCancelled']
        );
    }

    abstract protected function registerInfrastructureListeners(ConfigurableEventDispatcher $eventDispatcher): void;

    private function sendEmailListener(): SendEmail
    {
        return new SendEmail($this->userRepository(), $this->notifications(), $this->rsvpRepository());
    }

    abstract protected function notifications(): Notifications;
}
