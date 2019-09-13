<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Command;

use MeetupOrganizing\Infrastructure\ServiceContainer;
use PHPUnit_Framework_TestCase;
use Webmozart\Console\Args\StringArgs;
use Webmozart\Console\ConsoleApplication;
use Webmozart\Console\IO\OutputStream\BufferedOutputStream;

final class ScheduleMeetupConsoleHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_schedules_a_meetup(): void
    {
        $container = new ServiceContainer(getenv('PROJECT_ROOT_DIR'));

        $config = new MeetupApplicationConfig($container);
        $config->setTerminateAfterRun(false);
        $cli = new ConsoleApplication($config);

        $output = new BufferedOutputStream();
        $args = new StringArgs('schedule 1 Akeneo Meetup "2018-04-20 20:00"');
        $cli->run($args, null, $output);

        $this->assertContains(
            'Scheduled the meetup successfully',
            $output->fetch()
        );
    }
}
