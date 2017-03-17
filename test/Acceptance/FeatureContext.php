<?php

namespace Tests\Acceptance;

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext implements Context, SnippetAcceptingContext
{
    private $repository;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->repository = new InMemoryMeetupRepository();
    }

    /**
     * @When /^I schedule a "([^"]*)" with the description "([^"]*)" on "([^"]*)"$/
     */
    public function iScheduleAWithTheDescriptionOn(string $name, string $description, string $scheduledFor)
    {
        $handler = new ScheduleMeetupHandler($this->repository);
        $command = new ScheduleMeetup();
        $command->id = 'id';
        $command->name = $name;
        $command->description = $description;
        $command->scheduledFor = $scheduledFor;
        $handler->handle($command);
    }

    /**
     * @Then /^there will be an upcoming meetup called "([^"]*)" scheduled for "([^"]*)"$/
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor(string $name, string $scheduledFor)
    {
        $upcomingMeetups = $this->repository->upcomingMeetups(new \DateTimeImmutable());

        \PHPUnit_Framework_Assert::assertCount(1, $upcomingMeetups);

        $meetup = reset($upcomingMeetups);

        \PHPUnit_Framework_Assert::assertEquals($name, $meetup->name());
        \PHPUnit_Framework_Assert::assertEquals($scheduledFor, $meetup->scheduledFor()->format('Y-m-d'));
    }
}
