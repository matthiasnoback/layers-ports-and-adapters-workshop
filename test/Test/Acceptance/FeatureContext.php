<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use DateTimeImmutable;
use MeetupOrganizing\Application\ListMeetups\MeetupForList;
use MeetupOrganizing\Application\ScheduleMeetup\MeetupService;
use MeetupOrganizing\Application\ScheduleMeetup\ScheduleMeetup;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\Database\InMemoryMeetupRepository;
use MeetupOrganizing\Infrastructure\Database\InMemoryUserRepository;
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
        $meetupService = new MeetupService($this->userRepository, $this->meetupRepository);
        $meetupService->scheduleMeetup(
            new ScheduleMeetup(
                $this->userId,
                $name,
                'Description',
                $date . ' ' . $time
            )
        );
    }

    /**
     * @Then there will be an upcoming meetup called :name
     */
    public function thereWillBeAnUpcomingMeetupCalled(string $name): void
    {
        foreach ($this->meetupRepository->upcomingMeetups(new DateTimeImmutable()) as $upcomingMeetup) {
            /** @var MeetupForList $upcomingMeetup */
            if ($upcomingMeetup->name() === $name) {
                return;
            }
        }

        throw new RuntimeException('Could not find an upcoming meetup with name ' . $name);
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
