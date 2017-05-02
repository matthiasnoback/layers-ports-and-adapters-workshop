<?php
declare(strict_types=1);

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Domain\Model\Name;

final class ScheduleMeetupHandler
{
    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(MeetupRepository $meetupRepository)
    {
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(ScheduleMeetup $command): void
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString($command->id),
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );

        $this->meetupRepository->add($meetup);
    }
}
