<?php

namespace Test\System;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use MeetupOrganizing\SchemaManager;
use MeetupOrganizing\ServiceContainer;
use PHPUnit\Framework\Assert;
use rpkamp\Behat\MailhogExtension\Context\MailhogAwareContext;
use rpkamp\Mailhog\MailhogClient;
use rpkamp\Mailhog\Message\Contact;
use RuntimeException;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext extends MinkContext implements MailhogAwareContext
{
    private MailhogClient $mailhog;

    public function setMailhog(MailhogClient $client): void
    {
        $this->mailhog = $client;
        $this->mailhog->purgeMessages();
    }

    private ?string $scheduledMeetupId = null;

    private string $projectRootDir;

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
    public function truncateTables(): void
    {
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
        $this->selectOption('Logged in user', $name);
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
        $this->fillField('Schedule for date', '2024-10-10');
        $this->fillField('Time', '20:00');
        $this->pressButton('Schedule this meetup');

        $currentUrl = $this->getSession()->getCurrentUrl();
        $matches = [];
        if (preg_match('#^.+/(.+)$#', $currentUrl, $matches) === 0) {
            throw new RuntimeException('Cannot determine the ID of the meetup');
        }

        $this->scheduledMeetupId = $matches[1];
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
        $this->findOrFail('.attendees li:contains("' . $name . '")');
    }

    /**
     * @Then an email should be sent to :emailAddress with subject :subject
     */
    public function anEmailShouldBeSentTo(string $emailAddress, string $subject): void
    {
        $lastMessage = $this->mailhog->getLastMessage();

        $actualRecipients = array_map(
            fn (Contact $contact) => $contact->emailAddress,
            iterator_to_array($lastMessage->recipients)
        );
        Assert::assertContains(
            $emailAddress,
            $actualRecipients,
            sprintf(
                'Expected to find "%s" in the list of recipients (%s)',
                $emailAddress, implode(', ' , $actualRecipients)
            )
        );
        Assert::assertStringContainsString($subject, $lastMessage->subject);
    }

    private function findOrFail(string $cssLocator): NodeElement
    {
        $element = $this->getSession()->getPage()->find('css', $cssLocator);

        Assert::assertInstanceOf(
            NodeElement::class,
            $element,
            'Expected to find element with CSS selector: ' . $cssLocator
        );

        return $element;
    }
}
