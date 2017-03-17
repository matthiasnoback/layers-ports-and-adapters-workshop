<?php
declare(strict_types = 1);

namespace Meetup\Infrastructure\Cli;

use Meetup\Application\ScheduleMeetup;
use Meetup\Application\ScheduleMeetupHandler;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

final class ScheduleMeetupConsoleHandler
{
    /**
     * @var ScheduleMeetupHandler
     */
    private $scheduleMeetupHandler;

    public function __construct(ScheduleMeetupHandler $scheduleMeetupHandler)
    {
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
    }

    public function handle(Args $args, IO $io): int
    {
        $submittedData = [
            'name' => $args->getArgument('name'),
            'description' => $args->getArgument('description'),
            'scheduledFor' => $args->getArgument('scheduledFor')
        ];

        $errorsPerField = ScheduleMeetup::validate($submittedData);
        if (!empty($errorsPerField)) {
            foreach ($errorsPerField as $errors) {
                foreach ($errors as $error) {
                    $io->writeLine('<error>' . $error . '</error>');
                }
            }

            return 1;
        }

        $scheduleMeetup = new ScheduleMeetup();
        $scheduleMeetup->name = $submittedData['name'];
        $scheduleMeetup->description = $submittedData['description'];
        $scheduleMeetup->scheduledFor = $submittedData['scheduledFor'];

        $this->scheduleMeetupHandler->handle($scheduleMeetup);

        $io->writeLine('<success>Scheduled the meetup successfully</success>');

        return 0;
    }
}
