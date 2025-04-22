<?php

namespace App\Notifications;

use App\Models\Customers\ActivitiesModel;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentTasksNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected $activity;

    public function __construct(ActivitiesModel $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $expirationDate = new DateTime($this->activity->expiration_date); 
        $now = new DateTime();
        $expired = $now > $expirationDate;
        $message = "The ".$this->activity->txt_type." is about to expire";
        if($expired){
            $message = "The ".$this->activity->txt_type." has ".$this->activity->txt_remaining;
        }


        return [
            'message' => $message,
            'activity_id' => $this->activity->id,
            'url' =>  route('leads.details', ['id' => $this->activity->fk_customer, 'idActivity' => $this->activity->id])
        ];
    }
}
