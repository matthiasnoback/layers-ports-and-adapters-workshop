<?php

namespace Test\System;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use MeetupOrganizing\Infrastructure\SchemaManager;
use MeetupOrganizing\Infrastructure\ServiceContainer;
use RuntimeException;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext extends MinkContext
{
    /**
     * @var int|null
     */
    private $scheduledMeetupId;

    /**
     * @var string
     */
    private $projectRootDir;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(string $projectRootDir)
    {
        $this->projectRootDir = $projectRootDir;
    }

    /**
     * @BeforeScenario
     */
    public function updateSchema(): void
    {
        self::schemaManager()->updateSchema();
        self::schemaManager()->truncateTables();
    }

    /**
     * @return SchemaManager
     */
    private function schemaManager(): SchemaManager
    {
        $container = new ServiceContainer($this->projectRootDir);

        return $container[SchemaManager::class];
    }

    /**
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs(string $name): void
    {
        $this->visit('/');
        $this->selectOption('Logged in user', 'Regular user');
        $this->pressButton('Switch');
    }

    /**
     * @Given a meetup was scheduled
     */
    public function aMeetupWasScheduled(): void
    {
        $this->iAmLoggedInAs('Organizer');

        $this->visit('/schedule-meetup');
        $this->fillField('Name', 'Meetup');
        $this->fillField('Description', 'Description');
        $this->fillField('Schedule for date', '2020-10-10');
        $this->fillField('Time', '20:00');
        $this->pressButton('Schedule this meetup');

        $currentUrl = $this->getSession()->getCurrentUrl();
        $matches = [];
        if (preg_match('#^.+/(\d+)$#', $currentUrl, $matches) === 0) {
            throw new RuntimeException('Cannot determine the ID of the meetup');
        }

        $this->scheduledMeetupId = (int)$matches[1];
    }

    /**
     * @Given I am on the detail page of this meetup
     */
    public function iAmOnLookingAtTheDetailPageOfThisMeetup(): void
    {
        $this->visit('/meetup/' . $this->scheduledMeetupId);
    }

    /**
     * @Then the list of attendees should contain :name
     */
    public function theListOfAttendeesShouldContain(string $name): void
    {
        $attendeeElement = $this->getSession()->getPage()->find('css', '.attendees li:contains("' . $name . '")');
        assertNotNull($attendeeElement, 'Could not find the expected attendee element on the page');
    }
}
