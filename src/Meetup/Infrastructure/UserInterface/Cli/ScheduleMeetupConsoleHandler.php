<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\UserInterface\Cli;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Meetup\Domain\MeetupRepository;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $handler;

    /**
     * @var MeetupRepository
     */
    private $meetupRepository;

    public function __construct(ScheduleMeetupHandler $handler, MeetupRepository $meetupRepository)
    {
        $this->handler = $handler;
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(Args $args, IO $io): int
    {
        $command = new ScheduleMeetup();
        $command->id = (string)$this->meetupRepository->nextIdentity();
        $command->name = $args->getArgument('name');
        $command->description = $args->getArgument('description');
        $command->scheduledFor = $args->getArgument('scheduledFor');

        $errors = $command->validate();
        if (!empty($errors)) {
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $io->errorLine('<error>' . $field . ': ' . $error . '</error>');
                }
            }

            return 1;
        }

        $this->handler->handle($command);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
