<?php

namespace Tests\System;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

final class FeatureContext extends MinkContext implements Context
{
    /**
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn(string $name, string $description, string $scheduledFor): void
    {
        $this->visit('/schedule-meetup');

        $this->fillField('Name', $name);
        $this->fillField('Description', $description);
        $this->fillField('Schedule for', $scheduledFor);

        $this->pressButton('Schedule this meetup');
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor(string $name, string $scheduledFor): void
    {
        $this->visit('/');

        $this->assertPageContainsText('Upcoming meetups');
        $this->assertPageContainsText('Coding Dojo');
        $this->assertPageContainsText('Scheduled for ' . $scheduledFor);
    }
}
