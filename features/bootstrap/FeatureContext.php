<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use MeetupOrganizing\Entity\MeetupRepository;

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
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeFeature
     */
    public static function purgeDatabase(): void
    {
        $container = require __DIR__ . '/../../app/container.php';

        /** @var MeetupRepository $meetupRepository */
        $meetupRepository = $container[MeetupRepository::class];
        $meetupRepository->deleteAll();
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
    public function theListOfAttendeesShouldContain(string $name)
    {
        $attendeeElement = $this->getSession()->getPage()->find('css', '.attendees li:contains("' . $name . '")');
        assertNotNull($attendeeElement, 'Could not find the expected attendee element on the page');
    }
}
