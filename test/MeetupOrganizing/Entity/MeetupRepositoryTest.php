<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use MeetupOrganizing\Entity\Util\MeetupFactory;
use MeetupOrganizing\SchemaManager;
use PHPUnit_Framework_TestCase;

final class MeetupRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    /**
     * @var Connection
     */
    private $connection;

    protected function setUp()
    {
        $this->connection = DriverManager::getConnection(
            [
                'driver' => 'pdo_sqlite'
            ]
        );
        $schemaManager = new SchemaManager($this->connection);
        $schemaManager->updateSchema();

        $this->repository = new MeetupRepository($this->connection);
    }

    /**
     * @test
     */
    public function it_persists_and_retrieves_meetups(): void
    {
        $this->markTestIncomplete('Re-enable this test once you have a created the Meetup entity');

        $originalMeetup = MeetupFactory::someMeetup();
        $this->repository->add($originalMeetup);

        $this->assertGreaterThanOrEqual(1, $originalMeetup->meetupId());

        $restoredMeetup = $this->repository->getById($originalMeetup->meetupId());

        $this->assertEquals($originalMeetup, $restoredMeetup);
    }

    /**
     * @test
     */
    public function its_initial_state_is_valid(): void
    {
        $this->assertSame(
            [],
            $this->repository->upcomingMeetups(new DateTimeImmutable())
        );
    }

    /**
     * @test
     */
    public function it_lists_upcoming_meetups(): void
    {
        $now = new DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $upcomingMeetup
            ],
            $this->repository->upcomingMeetups($now)
        );
    }

    /**
     * @test
     */
    public function it_lists_past_meetups(): void
    {
        $now = new DateTimeImmutable();
        $pastMeetup = MeetupFactory::pastMeetup();
        $this->repository->add($pastMeetup);
        $upcomingMeetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($upcomingMeetup);

        $this->assertEquals(
            [
                $pastMeetup
            ],
            $this->repository->pastMeetups($now)
        );
    }

    protected function tearDown()
    {
        $this->connection->close();
    }
}
