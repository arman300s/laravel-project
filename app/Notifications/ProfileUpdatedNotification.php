<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileUpdatedNotification extends Notification
{
    use Queueable;

    public $updatedField;

    public function __construct($updatedField)
    {
        $this->updatedField = $updatedField;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => " 🔰Your profile has been updated. The field has been changed: {$this->updatedField}",
        ];
    }
}
