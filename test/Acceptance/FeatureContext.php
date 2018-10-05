<?php

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use MeetupOrganizing\Application\ScheduleMeetup;
use MeetupOrganizing\Application\ScheduleMeetupHandler;
use MeetupOrganizing\Domain\Model\MeetupRepository;
use MeetupOrganizing\Domain\Model\ScheduledDate;
use MeetupOrganizing\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;
use PHPUnit_Framework_Assert;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var MeetupRepository
     */
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
    }

    /**
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn(
        string $name,
        string $description,
        string $scheduledFor
    ) {
        $this->repository = new InMemoryMeetupRepository();
        $service = new ScheduleMeetupHandler($this->repository);

        $scheduleMeetup = new ScheduleMeetup();
        $scheduleMeetup->name = $name;
        $scheduleMeetup->description = $description;
        $scheduleMeetup->scheduledFor = $scheduledFor;

        $service->handle($scheduleMeetup);
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor(
        string $name,
        string $scheduledFor
    ) {
        $meetups = $this->repository->upcomingMeetups(new \DateTimeImmutable('2018-05-01'));

        foreach ($meetups as $meetup) {
            if ((string)$meetup->name() === $name) {
                PHPUnit_Framework_Assert::assertEquals(
                    ScheduledDate::fromPhpDateString($scheduledFor),
                    $meetup->scheduledFor()
                );
                return;
            }
        }

        throw new \RuntimeException(
            'No upcoming meetup found with name ' . $name
        );
    }
}
