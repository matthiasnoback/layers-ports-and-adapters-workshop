<?php
declare(strict_types = 1);

namespace Meetup\Entity;

use Assert\Assertion;

final class Description
{
    private $text;

    public static function fromString($text): Description
    {
        $description = new self();

        Assertion::notEmpty($text);
        $description->text = $text;

        return $description;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
