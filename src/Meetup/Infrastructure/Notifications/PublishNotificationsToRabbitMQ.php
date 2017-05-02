<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications;

use Bunny\Client;
use Meetup\Application\Notify;
use Meetup\Domain\Model\MeetupScheduled;
use NaiveSerializer\Serializer;

final class PublishNotificationsToRabbitMQ implements Notify
{
    public function meetupScheduled(MeetupScheduled $event): void
    {
        $connection = [
            'host' => 'rabbitmq',
            'vhost' => '/',
            'user' => 'guest',
            'password' => 'guest'
        ];

        $client = new Client($connection);
        $client->connect();

        $client->channel()->publish(
            Serializer::serialize($event)
        );
    }
}
