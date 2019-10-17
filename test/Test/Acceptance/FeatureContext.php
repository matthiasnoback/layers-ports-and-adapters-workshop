<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use MeetupOrganizing\Application\MeetupForList;
use MeetupOrganizing\Application\MeetupService;
use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Domain\MeetupRepository;
use MeetupOrganizing\Domain\UserRepository;
use MeetupOrganizing\Infrastructure\InMemoryUserRepository;
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

    public function __construct()
    {
        $this->userRepository = new InMemoryUserRepository();
    }

    /**
     * @Given I am an organizer
     */
    public function iAmAnOrganizer()
    {
        $this->userId = $this->userRepository->getOrganizerId()->asInt();
        $this->meetupRepository = new InMemoryMeetupRepository();
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

        $service->scheduleMeetup(
            new ScheduleMeetup(
                $this->userId,
                $name,
                'Some description',
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
            /** @var MeetupForList $meetup */
            if ($meetup->name() === $name) {
                return;
            }
        }

        throw new RuntimeException('We expected an upcoming meetup to have been scheduled');
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
