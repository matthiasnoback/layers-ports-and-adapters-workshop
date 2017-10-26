<?php

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Model\ScheduledDate;
use Meetup\Infrastructure\Notifications\Mute\MuteNotifications;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;

final class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new InMemoryMeetupRepository();
    }

    /**
     * @When I schedule a :name with the description :description on :scheduledFor
     */
    public function iScheduleAWithTheDescriptionOn($name, $description, $scheduledFor)
    {
        $command = new ScheduleMeetup();
        $command->id = (string)$this->repository->nextIdentity();
        $command->name = $name;
        $command->description = $description;
        $command->scheduledFor = $scheduledFor;

        $handler = new ScheduleMeetupHandler($this->repository, new MuteNotifications());
        $handler->handle($command);
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor($name, $scheduledFor)
    {
        $upcomingMeetups = $this->repository->upcomingMeetups(new \DateTimeImmutable('2017-05-02'));
        foreach ($upcomingMeetups as $meetup) {
            if ($meetup->name()->equals(Name::fromString($name)) &&
                $meetup->scheduledFor()->equals(ScheduledDate::fromPhpDateString($scheduledFor))) {
                return;
            }
        }

        throw new \RuntimeException('We found no upcoming meetups matching the arguments');
    }
}
