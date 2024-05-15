<?php

namespace App\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CustomDbNotification implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable;
  public function send($notifiable, Notification $notification)
  {

    $data = $notification->toArray($notifiable);
    //$notifiable->via_notification
    return $notifiable->routeNotificationFor('database')->create([
        'id' => $notification->id,
        'via' => 'broadcast',
        'title' => $data['title'],
        'content' => $data['content'],
        'type' => get_class($notification),
        'request_id' => $data['request_id'],
        'notification_deliver_id' => $data['notification_deliver_id'],
        'data' => $data,
        'read_at' => null
    ]);

  }

}
