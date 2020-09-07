<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use MeetupOrganizing\Entity\UserRepository;

final class FeatureContext implements Context
{
    private UserRepository $userRepository;

    private ?int $userId = null;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * @Given I am an organizer
     */
    public function iAmAnOrganizer(): void
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
    public function iCancelIt(): void
    {
        throw new PendingException();
    }

    /**
     * @Then it will no longer show up in the list of upcoming meetups
     */
    public function itWillNoLongerShowUpInTheListOfUpcomingMeetups(): void
    {
        throw new PendingException();
    }
}
