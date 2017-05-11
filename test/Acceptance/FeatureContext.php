<?php

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;
use Ramsey\Uuid\Uuid;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext implements Context
{
    private $meetupRepository;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->meetupRepository = new InMemoryMeetupRepository();
    }

    /**
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn(string $name, string $description, string $scheduledFor): void
    {
        $command = new ScheduleMeetup();
        $command->id = (string)Uuid::uuid4();
        $command->name = $name;
        $command->description = $description;
        $command->scheduledFor = $scheduledFor;

        $handler = new ScheduleMeetupHandler($this->meetupRepository);

        $handler->handle($command);
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor(string $name, string $scheduledFor): void
    {
        $upcomingMeetups = $this->meetupRepository->upcomingMeetups(new \DateTimeImmutable());

        foreach ($upcomingMeetups as $meetup) {
            if ((string)$meetup->name() == $name
                && new \DateTimeImmutable($scheduledFor) == $meetup->scheduledFor()) {
                return;
            }
        }

        throw new \RuntimeException('Fail');
    }
}
