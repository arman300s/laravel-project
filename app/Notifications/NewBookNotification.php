<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookNotification extends Notification
{
    use Queueable;

    public $bookTitle;

    public function __construct($bookTitle)
    {
        $this->bookTitle = $bookTitle;
    }

    public function via($notifiable)
    {
        return ['database']; // Уведомление через базу данных
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Новая книга: {$this->bookTitle} была добавлена!",
        ];
    }
}
