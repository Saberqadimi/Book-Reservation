<?php

namespace App\Notifications;

use App\Models\BookCopy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationWaitingList extends Notification
{
    use Queueable;
    private $bookCopy;
    /**
     * Create a new notification instance.
     */
    public function __construct(BookCopy $bookCopy)
    {
        $this->bookCopy = $bookCopy->load('book');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    //چون تایمم کم بود خیلی برای جزییات ارسال ایمیل و طراحی صفحه بلید و روت مربوطه نتونستم تایم بزارم
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('کتاب شما در دسترس است')
            ->greeting('سلام ' . $notifiable->name)
            ->line('کتاب "' . $this->bookCopy->book->title . '" اکنون در دسترس شما قرار دارد.')
            ->action('رزرو کتاب', url('/reservation/' . $this->bookCopy->id))
            ->line('برای رزرو کتاب روی دکمه بالا کلیک کنید.')
            ->line('با تشکر از شما که از خدمات ما استفاده می‌کنید!');
    }

}
