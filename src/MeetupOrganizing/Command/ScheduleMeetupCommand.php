<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ScheduleMeetupCommand extends Command
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure(): void
    {
        $this->setName('schedule')
            ->setDescription('Schedule a meetup')
            ->addArgument('organizerId', InputArgument::REQUIRED, 'Organizer ID')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the meetup')
            ->addArgument('description', InputArgument::REQUIRED, 'Description of the meetup')
            ->addArgument('scheduledFor', InputArgument::REQUIRED, 'Scheduled for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $record = [
            'organizerId' => (int)$input->getArgument('organizerId'),
            'name' => $input->getArgument('name'),
            'description' => $input->getArgument('description'),
            'scheduledFor' => $input->getArgument('scheduledFor')
        ];

        $this->connection->insert('meetups', $record);

        $output->writeln('<info>Scheduled the meetup successfully</info>');

        return 0;
    }
}
