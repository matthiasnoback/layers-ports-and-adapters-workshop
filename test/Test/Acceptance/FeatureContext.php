<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use MeetupOrganizing\Application\ScheduleMeetup\MeetupService;
use MeetupOrganizing\Application\ScheduleMeetup\ScheduleMeetup;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\Memory\InMemoryMeetupRepository;
use MeetupOrganizing\Infrastructure\Memory\InMemoryUserRepository;
use RuntimeException;

final class FeatureContext implements Context
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var int|null
     */
    private $userId;

    /**
     * @var InMemoryMeetupRepository
     */
    private $meetupRepository;

    /**
     * @var int|null
     */
    private $meetupId;

    public function __construct()
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->meetupRepository = new InMemoryMeetupRepository();
    }

    /**
     * @Given I am an organizer
     */
    public function iAmAnOrganizer()
    {
        $this->userId = $this->userRepository->getOrganizerId()->asInt();
    }

    /**
     * @When I schedule a :name on :date at :time
     * @Given I have scheduled a :name on :date at :time
     */
    public function iScheduleAWithTheDescriptionOnAt(string $name, string $date, string $time): void
    {
        $service = new MeetupService(
            $this->userRepository,
            $this->meetupRepository
        );

        $this->meetupId = $service->scheduleMeetup(
            new ScheduleMeetup(
                $this->userId,
                $name,
                'A description',
                $date . ' ' . $time
            )
        );
    }

    /**
     * @Then there will be an upcoming meetup called :name
     */
    public function thereWillBeAnUpcomingMeetupCalled(string $name): void
    {
        foreach ($this->meetupRepository->upcomingMeetups(new \DateTimeImmutable()) as $meetup) {
            if ($meetup->name() === $name) {
                return;
            }
        }

        throw new RuntimeException('We did not find the meetup we expected');
    }

    /**
     * @When I cancel it
     */
    public function iCancelIt()
    {
        throw new PendingException();
    }

    /**
     * @Then it will no longer show up in the list of upcoming meetups
     */
    public function itWillNoLongerShowUpInTheListOfUpcomingMeetups()
    {
        throw new PendingException();
    }
}
