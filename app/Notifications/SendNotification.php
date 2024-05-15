<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\CustomDbNotification;

class SendNotification extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $_title;
    private $_content;
    private $_request_id;
    private $_notification_deliver_id;

    public function __construct( object $data , $_request_id = null, $_notification_deliver_id = null)
    {
        $this->_title = $data->title;
        $this->_content = $data->content;
        $this->_request_id = $_request_id;
        $this->_notification_deliver_id = $_notification_deliver_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    //ViaEmai

    public function via($notifiable)
    {
        //['database','broadcast','mail']

        $_return  = [ CustomDbNotification::class, 'broadcast' ];

        if( $notifiable->via_email )
            array_push( $_return, 'mail' );

        return $_return;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

        try {
            return (new MailMessage)
            ->subject( $this->_title )
            ->view("mail.notication.send_notication", [ 'content' => $this->_content ] );
        } catch (\Throwable $th) {
            report($th);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->_title,
            'request_id' => $this->_request_id,
            // 'via' => $notifiable->via_notification
            'content'=> $this->_content,
            'invoice_id' => $this->id,
            'notification_deliver_id' => $this->_notification_deliver_id
        ];
    }

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'title' => $this->_title,
    //         //'via' => $notifiable->via_notification
    //         'content'=> $this->_content,
    //         'invoice_id' => $this->id
    //     ];
    // }

    public function toBroadcast($notifiable)
    {

        return new BroadcastMessage([
            'id' => $this->id,
            'title' => $this->_title,
            'content' => $this->_content,
            'created_at' => Carbon::now()->format('Y年m月d日 H:i'),
            'type' => 'Notification',
            'amount' => $this->id,
            'request_id' => $this->_request_id,
            'count_unread' => $notifiable->unreadNotifications()->where('via','broadcast')->count()
        ]);

        // return (new BroadcastMessage($data))
        //         ->onConnection('sqs')
        //         ->onQueue('broadcasts');

    }

}
