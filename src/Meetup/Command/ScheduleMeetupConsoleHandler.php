<?php

namespace Meetup\Command;

use Meetup\Entity\Description;
use Meetup\Entity\Meetup;
use Meetup\Entity\MeetupId;
use Meetup\Entity\MeetupRepository;
use Meetup\Entity\Name;
use Ramsey\Uuid\Uuid;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Args $args, IO $io)
    {
        $meetup = Meetup::schedule(
            MeetupId::fromString((string) Uuid::uuid4()),
            Name::fromString($args->getArgument('name')),
            Description::fromString($args->getArgument('description')),
            new \DateTimeImmutable($args->getArgument('scheduledFor'))
        );
        $this->repository->add($meetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
