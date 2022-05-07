<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellingRequestApprovalMail extends Notification
{
    use Queueable;
    public $req_id;
    public $req_status;
    public $footer_message;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($req_id,$req_status, $footer_message)
    {
        $this->req_id = $req_id;
        $this->req_status = $req_status;
        $this->footer_message= $footer_message;
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
                    ->subject('Selling Request '.$this->req_status)
                    ->line('Your selling request ('.$this->req_id.') has been '.$this->req_status.'.')
                    ->line($this->footer_message);
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
