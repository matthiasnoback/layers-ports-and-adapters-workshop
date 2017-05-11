<?php
declare(strict_types=1);

namespace Meetup\Application;

use Meetup\Domain\Description;
use Meetup\Domain\Meetup;
use Meetup\Domain\MeetupId;
use Meetup\Domain\MeetupRepository;
use Meetup\Domain\Name;

final class ScheduleMeetupHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ScheduleMeetup $command): Meetup
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString($command->id),
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );

        $this->repository->add($meetup);

        return $meetup;
    }
}
