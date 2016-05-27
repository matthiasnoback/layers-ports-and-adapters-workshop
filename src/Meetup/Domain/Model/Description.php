<?php

namespace Meetup\Domain\Model;

use Assert\Assertion;

final class Description
{
    private $text;

    public static function fromString($text)
    {
        $description = new self();

        Assertion::notEmpty($text);
        $description->text = $text;

        return $description;
    }

    public function __toString()
    {
        return $this->text;
    }
}
