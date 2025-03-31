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
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => '❗The book "' . $this->bookTitle . '" was deleted by the administrator.',
        ];
    }
}
