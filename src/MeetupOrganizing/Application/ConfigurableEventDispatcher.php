<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\EventDispatcher;

final class ConfigurableEventDispatcher implements EventDispatcher
{
    /**
     * @var array<callable>
     */
    private array $genericListeners = [];

    /**
     * @var array<class-string,array<callable>>
     */
    private array $listenersPerEvent = [];

    public function __construct()
    {
    }

    /**
     * @param array<callable> $genericListeners
     */
    public function registerGenericListeners(array $genericListeners): void
    {
        $this->genericListeners = $genericListeners;
    }

    /**
     * @param class-string $event
     */
    public function registerSpecificListener(string $event, callable $listener): void
    {
        $this->listenersPerEvent[$event][] = $listener;
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch(object $event): void
    {
        foreach ($this->genericListeners as $listener) {
            $this->notifyListener($listener, $event);
        }

        foreach ($this->listenersPerEvent[get_class($event)] ?? [] as $listener) {
            $this->notifyListener($listener, $event);
        }
    }

    private function notifyListener(callable $listener, object $event): void
    {
        $result = $listener($event);
        if (is_callable($result)) {
            $result($event);
        }
    }
}
