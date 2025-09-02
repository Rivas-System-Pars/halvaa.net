<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class InstallmentNotification extends Notification
{
    use Queueable;
	
	public $bodyId;
	
	public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bodyId,$data)
    {
        $this->bodyId= $bodyId;
		$this->data= $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return [
            'mobile'       => $notifiable->username,
            'data'         => [
				'bodyId' => $this->bodyId,
                'arr_data' => $this->data,
            ],
            'type'         => Sms::TYPES['INSTALLMENT_NOTIFICATION'],
            'user_id'      => $notifiable->id
        ];
    }

    public function databaseType()
    {
        return 'INSTALLMENT_NOTIFICATION';
    }
}
