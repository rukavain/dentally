<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewAppointmentNotification extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'New appointment request ' . optional($this->appointment->patient)->name,
            'patient_name' => optional($this->appointment->patient)->name ?? 'Unknown Patient',
            'appointment_date' => $this->appointment->appointment_date,
            'preferred_time' => $this->appointment->preferred_time,
            'procedure_name' => optional($this->appointment->procedure)->name ?? 'Unknown Procedure',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
