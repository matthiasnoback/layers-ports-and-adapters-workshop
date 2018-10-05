<?php
declare(strict_types = 1);

namespace MeetupOrganizing\Entity;

final class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_wraps_a_string(): void
    {
        $descriptionText = 'Non-empty string';
        $description = Description::fromString($descriptionText);
        $this->assertEquals($descriptionText, (string)$description);
    }

    /**
     * @test
     */
    public function it_should_be_a_non_empty_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Description::fromString('');
    }
}
