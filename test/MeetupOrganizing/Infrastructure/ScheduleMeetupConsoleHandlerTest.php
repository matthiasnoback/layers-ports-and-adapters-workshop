<?php
declare(strict_types=1);

namespace Tests\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Infrastructure\UserInterface\Cli\MeetupApplicationConfig;
use Webmozart\Console\Args\StringArgs;
use Webmozart\Console\ConsoleApplication;
use Webmozart\Console\IO\OutputStream\BufferedOutputStream;

final class ScheduleMeetupConsoleHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_schedules_a_meetup(): void
    {
        $container = require __DIR__ . '/../../../app/container.php';

        $config = new \MeetupOrganizing\Infrastructure\UserInterface\Cli\MeetupApplicationConfig($container);
        $config->setTerminateAfterRun(false);
        $cli = new ConsoleApplication($config);

        $output = new BufferedOutputStream();
        $args = new StringArgs('schedule Akeneo Meetup "2018-04-20 20:00"');
        $cli->run($args, null, $output);

        $this->assertContains(
            'Scheduled the meetup successfully',
            $output->fetch()
        );
    }
}
