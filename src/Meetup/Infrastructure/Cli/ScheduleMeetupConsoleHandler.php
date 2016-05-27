<?php

namespace Meetup\Infrastructure\Cli;

use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\Meetup;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\MeetupRepository;
use Meetup\Domain\Model\Name;
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
