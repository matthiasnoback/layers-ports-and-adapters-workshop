<?php

namespace MeetupOrganizing;

interface EventDispatcher
{
    /**
     * @param array<object> $events
     */
    public function dispatchAll(array $events): void;

    public function dispatch(object $event): void;
}
