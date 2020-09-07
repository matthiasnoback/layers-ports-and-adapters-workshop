<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

final class ConsoleApplication extends Application
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct('MeetupOrganizing', 'v1.0.0');

        $this->container = $container;

        $this->addCommands(
            [
                new ScheduleMeetupCommand($this->container->get(Connection::class))
            ]
        );
    }
}
