<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Appointment Approved')
                    ->greeting('Hello ' . $this->appointment->first_name . ' ' . $this->appointment->last_name . ',')
                    ->line('Your appointment request has been approved.')
                    ->line('Appointment Date: ' . $this->appointment->appointment_date)
                    ->line('Preferred Time: ' . $this->appointment->preferred_time)
                    ->line('Branch: ' . $this->appointment->branch)
                    ->line('Thank you for choosing our clinic!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
