<?php
declare(strict_types = 1);

namespace Tests\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Domain\Model\MeetupId;
use MeetupOrganizing\Infrastructure\Persistence\FileSystem\FileBasedMeetupRepository;
use Tests\MeetupOrganizing\Domain\Model\Util\MeetupFactory;

final class FileBasedMeetupRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileBasedMeetupRepository
     */
    private $repository;

    private $filePath;

    protected function setUp()
    {
        $this->filePath = tempnam(sys_get_temp_dir(), 'meetups');
        $this->repository = new FileBasedMeetupRepository($this->filePath);
    }

    /**
     * @test
     */
    public function it_persists_and_retrieves_meetups()
    {
        $originalMeetup = MeetupFactory::someMeetup();
        $this->repository->add($originalMeetup);

        $restoredMeetup = $this->repository->byId(MeetupId::fromString($originalMeetup->id()));

        $this->assertEquals($originalMeetup, $restoredMeetup);
    }

    /**
     * @test
     */
    public function its_initial_state_is_valid()
    {
        $this->assertSame(
            [],
            $this->repository->upcomingMeetups(new \DateTimeImmutable())
        );
    }

    /**
     * @test
     */
    public function it_lists_upcoming_meetups()
    {
        $now = new \DateTimeImmutable();
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
    public function it_lists_past_meetups()
    {
        $now = new \DateTimeImmutable();
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

    /**
     * @test
     */
    public function it_can_delete_all_meetups()
    {
        $meetup = MeetupFactory::upcomingMeetup();
        $this->repository->add($meetup);
        $this->assertEquals([$meetup], $this->repository->allMeetups());

        $this->repository->deleteAll();

        $this->assertEquals([], $this->repository->upcomingMeetups(new \DateTimeImmutable()));
        $this->assertEquals([], $this->repository->pastMeetups(new \DateTimeImmutable()));
    }

    protected function tearDown()
    {
        unlink($this->filePath);
    }
}
