<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentDeclined extends Notification
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
        $appointment = $this->appointment;
        $procedureName = $appointment->procedure ? $appointment->procedure->name : 'Not specified';
        $formattedDate = date('F j, Y', strtotime($appointment->appointment_date));

        return (new MailMessage)
            ->subject('Update Regarding Your Dental Appointment Request')
            ->greeting('Hello ' . $appointment->patient->first_name . ',')
            ->line('We appreciate your interest in scheduling an appointment with Tooth Impression\'s Dental Clinic.')
            ->line('Unfortunately, we are unable to accommodate your appointment request for the following details:')
            ->line('Date: ' . $formattedDate)
            ->line('Time: ' . $appointment->preferred_time)
            ->line('Procedure: ' . $procedureName)
            ->line('Branch: ' . $appointment->branch->branch_loc)
            ->line('This could be due to various reasons such as:')
            ->line('1. Schedule conflicts')
            ->line('2. Dentist availability')
            ->line('3. Equipment maintenance')
            ->action('Schedule New Appointment', url('/client/dashboard/overview', $appointment->patient_id))
            ->line('We encourage you to schedule a new appointment at your convenience. Our team is ready to assist you in finding a suitable time.')
            ->line('If you have any questions or need immediate assistance, please don\'t hesitate to contact us.')
            ->salutation('Best regards,' . "\n" . 'The Tooth Impression\'s Dental Clinic Team');
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
