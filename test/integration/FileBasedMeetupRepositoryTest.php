<?php

namespace Tests\Integration\Meetup\Infrastructure\Persistence\Filesystem;

use Meetup\Domain\Model\MeetupId;
use Meetup\Infrastructure\Persistence\Filesystem\FileBasedMeetupRepository;
use Tests\Unit\Meetup\Domain\Model\Util\MeetupFactory;

class FileBasedMeetupRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $meetupId = MeetupId::fromString('id');
        $originalMeetup = MeetupFactory::someMeetupWithId($meetupId);
        $this->repository->add($originalMeetup);

        $restoredMeetup = $this->repository->byId($meetupId);

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

    protected function tearDown()
    {
        unlink($this->filePath);
    }
}
