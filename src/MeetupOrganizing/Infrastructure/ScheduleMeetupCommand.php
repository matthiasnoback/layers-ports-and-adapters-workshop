<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use MeetupOrganizing\Application\MeetupOrganizingInterface;
use MeetupOrganizing\Application\ScheduleMeetup;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ScheduleMeetupCommand extends Command
{
    private MeetupOrganizingInterface $meetupOrganizing;

    public function __construct(MeetupOrganizingInterface $meetupService)
    {
        parent::__construct();

        $this->meetupOrganizing = $meetupService;
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

        $this->meetupOrganizing->scheduleMeetup(
            new ScheduleMeetup(
                (int)$organizerId,
                $name,
                $description,
                $scheduledFor
            )
        );

        $output->writeln('<info>Scheduled the meetup successfully</info>');

        return 0;
    }
}
