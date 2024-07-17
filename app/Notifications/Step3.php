<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class Step3 extends Notification
{
    use Queueable;

    protected $notify_data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $notify_data )
    {
        $this->notify_data = $notify_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->notify_data['subject'])
            ->line(new HtmlString($this->notify_data['intro']))
            ->line(new HtmlString($this->notify_data['message']))
            ->line(new HtmlString($this->notify_data['outtro']))
            ->action($this->notify_data['url_display'], $this->notify_data['url']);
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
            //
        ];
    }
}
