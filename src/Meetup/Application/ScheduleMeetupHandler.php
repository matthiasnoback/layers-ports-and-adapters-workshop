<?php
declare(strict_types=1);

namespace Meetup\Application;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\Name;
use Meetup\Infrastructure\MeetupRepository;

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

    public function handle(ScheduleMeetup $command): Meetup
    {
        $meetup = Meetup::schedule(
            Name::fromString($command->name),
            Description::fromString($command->description),
            new \DateTimeImmutable($command->scheduledFor)
        );

        $this->meetupRepository->add($meetup);

        return $meetup;
    }
}
