<?php
declare(strict_types = 1);

namespace Tests\Meetup\Entity;

use Meetup\Domain\Model\MeetupRepository;
use Tests\Meetup\Entity\Util\MeetupFactory;

final class MeetupRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MeetupRepository
     */
    private $repository;

    private $filePath;

    protected function setUp()
    {
        $this->filePath = tempnam(sys_get_temp_dir(), 'meetups');
        $this->repository = new MeetupRepository($this->filePath);
    }

    /**
     * @test
     */
    public function it_persists_and_retrieves_meetups()
    {
        $originalMeetup = MeetupFactory::someMeetup();
        $this->repository->add($originalMeetup);

        $this->assertInternalType('int', $originalMeetup->id());
        $this->assertGreaterThanOrEqual(1, $originalMeetup->id());

        $restoredMeetup = $this->repository->byId($originalMeetup->id());

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
