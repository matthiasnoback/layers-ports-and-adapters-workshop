<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\InMemoryUserRepository;

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
    }

    /**
     * @When I schedule a :name on :date at :time
     * @Given I have scheduled a :name on :date at :time
     */
    public function iScheduleAWithTheDescriptionOnAt(string $name, string $date, string $time): void
    {
        throw new PendingException();
    }

    /**
     * @Then there will be an upcoming meetup called :name
     */
    public function thereWillBeAnUpcomingMeetupCalled(string $name): void
    {
        throw new PendingException();
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
