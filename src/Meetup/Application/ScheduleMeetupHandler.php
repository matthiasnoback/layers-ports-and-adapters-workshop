<?php
declare(strict_types = 1);

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;
use Meetup\Domain\Repository\MeetupRepository;

final class ScheduleMeetupHandler
{
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ScheduleMeetup $command): void
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString($command->id),
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );
        $this->repository->add($meetup);
    }
}
