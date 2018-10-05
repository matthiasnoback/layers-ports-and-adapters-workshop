<?php
declare(strict_types = 1);

namespace Tests\MeetupOrganizing\Domain\Model;

use MeetupOrganizing\Domain\Model\Description;

final class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_wraps_a_string()
    {
        $descriptionText = 'Non-empty string';
        $description = Description::fromString($descriptionText);
        $this->assertEquals($descriptionText, (string)$description);
    }

    /**
     * @test
     */
    public function it_should_be_a_non_empty_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        Description::fromString('');
    }
}
