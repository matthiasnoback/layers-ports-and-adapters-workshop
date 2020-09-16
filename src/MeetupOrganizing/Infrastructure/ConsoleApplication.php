<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

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
                $this->container->get(ScheduleMeetupCommand::class)
            ]
        );
    }
}
