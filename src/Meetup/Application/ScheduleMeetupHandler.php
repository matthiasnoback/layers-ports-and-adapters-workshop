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
    /**
     * @var Notify
     */
    private $notify;

    public function __construct(MeetupRepository $repository, Notify $notify)
    {
        $this->repository = $repository;
        $this->notify = $notify;
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

        $this->notify->meetupScheduled(
            $meetup->id(),
            $meetup->name(),
            $meetup->description(),
            $meetup->scheduledFor()
        );
    }
}
