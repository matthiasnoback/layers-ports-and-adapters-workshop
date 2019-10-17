<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use MeetupOrganizing\Entity\Meetup;
use MeetupOrganizing\Entity\ScheduledDate;
use MeetupOrganizing\Entity\UserId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ScheduleMeetupCommand extends Command
{
    private Connection $connection;

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
        // InputInterface isn't particularly well-typed, so we need to add some checks
        $organizerId = $input->getArgument('organizerId');
        Assert::that($organizerId)->string();


        $name = $input->getArgument('name');
        Assert::that($name)->string();

        $description = $input->getArgument('description');
        Assert::that($description)->string();

        $scheduledFor = $input->getArgument('scheduledFor');
        Assert::that($scheduledFor)->string();

        $meetup = new Meetup(
            UserId::fromInt((int)$organizerId),
            $name,
            $description,
            ScheduledDate::fromString($scheduledFor)
        );

        $this->connection->insert('meetups', $meetup->getData());

        $output->writeln('<info>Scheduled the meetup successfully</info>');

        return 0;
    }
}
