<?php
declare(strict_types=1);

namespace Meetup\Infrastructure\Notifications\RabbitMQ;

use Bunny\Client;
use Meetup\Application\Notify;
use Meetup\Domain\Model\Description;
use Meetup\Domain\Model\MeetupId;
use Meetup\Domain\Model\Name;

final class MessageNotifier implements Notify
{
    public function meetupScheduled(
        MeetupId $meetupId,
        Name $name,
        Description $description,
        \DateTimeImmutable $scheduledFor
    ): void {

        $connection = [
            'host' => 'rabbitmq',
            'vhost' => '/',
            'user' => 'guest',
            'password' => 'guest'
        ];

        $client = new Client($connection);
        $client->connect();

        $channel = $client->channel();
        $channel->queueDeclare('queue_name');

        $message = sprintf(
            'Notification: meetup scheduled for %s: %s',
            $name,
            $scheduledFor->format(\DateTime::ATOM)
        );

        $channel->publish(
            $message,
            [],
            '',
            'queue_name'
        );
    }
}
