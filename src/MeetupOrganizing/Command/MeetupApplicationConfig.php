<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Command;

use Interop\Container\ContainerInterface;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Formatter\Style;
use Webmozart\Console\Config\DefaultApplicationConfig;

final class MeetupApplicationConfig extends DefaultApplicationConfig
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('meetup')
            ->setVersion('1.0.0')
            ->addStyle(Style::tag('success')->fgGreen())
            ->beginCommand('schedule')
                ->setDescription('Schedule a meetup')
                ->addArgument('name', Argument::REQUIRED, 'Name')
                ->addArgument('description', Argument::REQUIRED, 'Description')
                ->addArgument('scheduledFor', Argument::REQUIRED, 'Scheduled for')
                ->setHandler(function () {
                    return $this->container->get(ScheduleMeetupConsoleHandler::class);
                })
            ->end();
    }
}
