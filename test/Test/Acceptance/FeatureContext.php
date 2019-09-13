<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use MeetupOrganizing\Infrastructure\HardCodedUserRepository;
use MeetupOrganizing\Domain\UserRepository;

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
        $this->userRepository = new HardCodedUserRepository();
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
}
