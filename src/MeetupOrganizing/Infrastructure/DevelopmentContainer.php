<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Doctrine\DBAL\Connection;
use MeetupOrganizing\Application\AbstractContainer;
use MeetupOrganizing\Application\Clock;
use MeetupOrganizing\Application\ConfigurableEventDispatcher;
use MeetupOrganizing\Application\ListMeetupsRepository;
use MeetupOrganizing\Domain\MeetupWasScheduled;
use MeetupOrganizing\Domain\UserHasRsvpd;
use MeetupOrganizing\Domain\UserRepository;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

final class DevelopmentContainer extends AbstractContainer
{
    private Connection $connection;
    private Session $session;

    public function __construct(Connection $connection, Session $session)
    {
        $this->connection = $connection;
        $this->session = $session;
    }

    protected function userRepository(): UserRepository
    {
        return new UserRepository();
    }

    protected function meetupRepository(): MeetupRepositoryUsingDbal
    {
        return new MeetupRepositoryUsingDbal($this->connection);
    }

    protected function rsvpRepository(): RsvpRepositoryUsingDbal
    {
        return new RsvpRepositoryUsingDbal($this->connection);
    }

    protected function clock(): Clock
    {
        return new SystemClock();
    }

    protected function listMeetupsRepository(): ListMeetupsRepository
    {
        return $this->meetupRepository();
    }

    protected function notifications(): NotificationsUsingSymfonyMailer
    {
        return new NotificationsUsingSymfonyMailer(
            new Mailer(
                Transport::fromDsn('smtp://mailhog:1025')
            )
        );
    }

    protected function registerInfrastructureListeners(ConfigurableEventDispatcher $eventDispatcher): void
    {
        $eventDispatcher->registerSpecificListener(
            UserHasRsvpd::class,
            function () {
                $this->session->addSuccessFlash('You have successfully RSVP-ed to this meetup');
            }
        );

        $eventDispatcher->registerSpecificListener(
            MeetupWasScheduled::class,
            function () {
                $this->session->addSuccessFlash('Your meetup was scheduled successfully');
            }
        );
    }
}
