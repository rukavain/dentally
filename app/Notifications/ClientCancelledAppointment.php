<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ClientCancelledAppointment extends Notification
{
    use Queueable;
    protected $appointment;
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Cancelled Appointment',
            'message' => 'Appointment cancelled by' . $this->appointment->patient->first_name . ' ' . $this->appointment->patient->last_name,
            'apointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->first_name . ' ' . $this->appointment->patient->last_name,
            'procedure_name' => $this->appointment->procedure->name,
            'appointment_date' => $this->appointment->appointment_date,
            'type' => 'appointment_cancelled'

        ];
    }
}
