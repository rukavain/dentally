<?php

// tests/Unit/AppointmentTest.php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_appointment_can_be_created()
    {
        $branch = Branch::factory()->create();
        $patient = Patient::factory()->create();
        $dentist = Dentist::factory()->create(['branch_id' => $branch->id]);
        $schedule = Schedule::factory()->create([
            'branch_id' => $branch->id,
            'dentist_id' => $dentist->id,
        ]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'branch_id' => $branch->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => $schedule->date,
            'preferred_time' => $schedule->start_time,
            'status' => 'Scheduled',
            'pending' => 'Pending',
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'Scheduled',
        ]);
    }
}

