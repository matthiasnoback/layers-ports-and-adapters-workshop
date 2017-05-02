<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Behat\Behat\Context\Context;
use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Infrastructure\Notifications\MuteNotifications;
use Meetup\Infrastructure\Persistence\InMemory\InMemoryMeetupRepository;

final class FeatureContext implements Context
{
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
        $handler = new ScheduleMeetupHandler($this->repository, new MuteNotifications());

        $command = new ScheduleMeetup();
        $command->id = (string)$this->repository->nextIdentity();
        $command->name = $name;
        $command->description = $description;
        $command->scheduledFor = $scheduledFor;

        $handler->handle($command);
    }

    /**
     * @Then there will be an upcoming meetup called :name scheduled for :scheduledFor
     */
    public function thereWillBeAnUpcomingMeetupCalledScheduledFor($name, $scheduledFor)
    {
        $upcomingMeetups = $this->repository->upcomingMeetups(new \DateTimeImmutable('2017-05-02'));

        foreach ($upcomingMeetups as $meetup) {
            if ((string)$meetup->name() === $name &&
                $meetup->scheduledFor() == new \DateTimeImmutable($scheduledFor)) {
                return;
            }
        }

        throw new \RuntimeException('We found no upcoming meetups matching the arguments');
    }
}
