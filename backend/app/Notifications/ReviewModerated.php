<?php

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewModerated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Review $review)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->review->status);
        $reason = $this->review->rejection_reason;

        $message = (new MailMessage)
            ->subject("Your review was {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your review for business ID {$this->review->business_id} was {$this->review->status}.");

        if ($this->review->status === Review::STATUS_REJECTED && $reason) {
            $message->line("Reason: {$reason}");
        }

        return $message
            ->line('Thank you for contributing to our community.');
    }
}
