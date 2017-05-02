<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class FeatureContext implements Context
{
    public function __construct()
    {
    }

    /**
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn($name, $description, $scheduledFor)
    {
        throw new PendingException();
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor($name, $scheduledFor)
    {
        throw new PendingException();
    }
}
