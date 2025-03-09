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
        return ['database']; // Уведомление через базу данных
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Ваш профиль был обновлен. Изменено поле: {$this->updatedField}",
        ];
    }
}
