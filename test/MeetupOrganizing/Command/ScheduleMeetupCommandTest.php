<?php
declare(strict_types=1);

namespace MeetupOrganizing\Command;

use Assert\Assert;
use MeetupOrganizing\Infrastructure\Cli\ConsoleApplication;
use MeetupOrganizing\Infrastructure\MySql\SchemaManager;
use MeetupOrganizing\Infrastructure\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

final class ScheduleMeetupCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_schedules_a_meetup(): void
    {
        $projectRootDir = getenv('PROJECT_ROOT_DIR');
        Assert::that($projectRootDir)->string();

        $container = new ServiceContainer($projectRootDir);
        $application = new ConsoleApplication($container);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $applicationTester = new ApplicationTester($application);

        $exitCode = $applicationTester->run(
            [
                'command' => 'schedule',
                'organizerId' => '1',
                'name' => 'Akeneo Meetup',
                'description' => 'The description',
                'scheduledFor' => '2024-04-20 20:00'
            ]
        );
        /** @var SchemaManager $schemaManager */
        $schemaManager = $container[SchemaManager::class];
        $schemaManager->updateSchema();

        self::assertSame(0, $exitCode);

        $this->assertStringContainsString(
            'Scheduled the meetup successfully',
            $applicationTester->getDisplay()
        );
    }
}
