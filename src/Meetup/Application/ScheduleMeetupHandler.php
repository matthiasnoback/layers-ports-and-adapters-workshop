<?php
declare(strict_types = 1);

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Domain\Model\Name;

final class ScheduleMeetupHandler
{
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ScheduleMeetup $command): Meetup
    {
        $meetup = Meetup::schedule(
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );
        $this->repository->add($meetup);

        return $meetup;
    }
}
