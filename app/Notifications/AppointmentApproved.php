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
        $appointment = $this->appointment;
        $procedureName = $appointment->procedure ? $appointment->procedure->name : 'Not specified';
        $dentistName = $appointment->dentist ? $appointment->dentist->full_name : 'Not assigned';
        $formattedDate = date('F j, Y', strtotime($appointment->appointment_date));

        return (new MailMessage)
            ->subject('Your Dental Appointment has been Confirmed!')
            ->greeting('Hello ' . $appointment->patient->first_name . '!')
            ->line('Great news! Your appointment request has been approved.')
            ->line('Here are the details of your upcoming visit:')
            ->line('Date: ' . $formattedDate)
            ->line('Time: ' . $appointment->preferred_time)
            ->line('Procedure: ' . $procedureName)
            ->line('Dentist: ' . $appointment->dentist->dentist_first_name . ' ' . $appointment->dentist->dentist_last_name)
            ->line('Branch: ' . $appointment->branch->branch_loc)
            ->line('Important Reminders:')
            ->line('1. Please arrive 10-15 minutes before your scheduled appointment')
            ->line('2. Bring any relevant medical records or x-rays')
            ->line('3. If you need to reschedule, please notify us at least 24 hours in advance')
            ->action('View Appointment Details', url('/client/dashboard/overview', $appointment->patient_id))
            ->line('Thank you for choosing Tooth Impression\'s Dental Clinic. We look forward to seeing you!')
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
