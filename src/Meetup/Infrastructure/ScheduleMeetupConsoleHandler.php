<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure;

use Meetup\Domain\Description;
use Meetup\Domain\Meetup;
use Meetup\Infrastructure\MeetupRepository;
use Meetup\Domain\Name;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    public function __construct(MeetupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Args $args, IO $io): int
    {
        $meetup = Meetup::schedule(
            Name::fromString($args->getArgument('name')),
            Description::fromString($args->getArgument('description')),
            new \DateTimeImmutable($args->getArgument('scheduledFor'))
        );
        $this->repository->add($meetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
