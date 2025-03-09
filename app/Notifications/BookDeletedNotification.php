<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class BookDeletedNotification extends Notification
{
    private $bookTitle;

    public function __construct($bookTitle)
    {
        $this->bookTitle = $bookTitle;
    }

    public function via($notifiable)
    {
        return ['database'];  // Здесь мы будем использовать базу данных для уведомлений
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Книга "' . $this->bookTitle . '" была удалена администратором.',
        ];
    }
}
