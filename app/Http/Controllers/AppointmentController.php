<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DentistSchedule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\AppointmentApproved;
use App\Notifications\AppointmentDeclined;
use Illuminate\Support\Facades\Notification;

class AppointmentController extends Controller
{
    public function create()
    {

        $branches = Branch::all();
        $patients = Patient::all();

        return view('appointment.create', [
            'branches' => $branches,
            'patients' => $patients,
        ]);
    }

    public function show($id){
        $appointment = Appointment::find($id);

        return view('appointment.appointment-information', compact('appointment'));
    }

    public function addWalkIn()
    {
        $branches = Branch::all();
        $patients = Patient::all();
        $procedures = Procedure::all();

        return view('appointment.add-walk-in-appointment', [
            'branches' => $branches,
            'patients' => $patients,
            'procedures' => $procedures,
        ]);
    }

    public function addOnline($id)
    {
        $patient = Patient::findOrFail($id);
        $branches = Branch::all();
        $procedures = Procedure::all();

        return view('appointment.add-online-appointment', [
            'branches' => $branches,
            'patient' => $patient,
            'procedures' => $procedures,
        ]);
    }
    
    //working
    public function storeWalkIn(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'dentist_id' => 'required',
            'branch_id' => 'required',
            'schedule_id' => 'required',
            'proc_id' => 'required',
            'appointment_date' => 'required|date',
            'preferred_time' => 'required',
            'is_online' => 'boolean',
        ]);

        // Check for existing appointments to prevent duplicates
        $existingAppointment = Appointment::where('appointment_date', $validatedData['appointment_date'])
                ->where('preferred_time', $validatedData['preferred_time'])
                ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['error' => 'This appointment slot is already taken.']);
        }

        $existingAppointment = Appointment::where('patient_id', $validatedData['patient_id'])
        ->where('appointment_date', $validatedData['appointment_date'])
        ->where('preferred_time', $validatedData['preferred_time'])
        ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['error' => 'This appointment slot is already taken for this patient.']);
        }

        $patientAppointment = Appointment::where('patient_id', $validatedData['patient_id'])
        ->where('appointment_date', $validatedData['appointment_date'])
        ->first();

        if ($patientAppointment) {
            return redirect()->back()->withErrors(['error' => 'This patient already has an appointment on this date.']);
        }

        // Check if the patient already has a pending appointment
        $pendingAppointment = Appointment::where('patient_id', $validatedData['patient_id'])
        ->where('pending', 'Pending') // Check for pending status
        ->first();

        if ($pendingAppointment) {
        return redirect()->back()->withErrors(['error' => 'This patient already has a pending appointment. Please resolve it before scheduling a new one.']);
        }

        // Create the new appointment record
        $appointment = Appointment::create([
            'patient_id' => $validatedData['patient_id'],
            'dentist_id' => $validatedData['dentist_id'],
            'branch_id' => $validatedData['branch_id'],
            'schedule_id' => $validatedData['schedule_id'],
            'proc_id' => $validatedData['proc_id'],
            'appointment_date' => $validatedData['appointment_date'],
            'preferred_time' => $validatedData['preferred_time'],
            'status' => 'scheduled',
            'pending' => 'pending',
            'is_online' => $validatedData['is_online'],
        ]);

        return redirect()->route('appointments.walkIn')->with('success', 'Appointment successfully created!');
        session()->flash('success', 'Appointment added successfully!');
    }


    public function storeOnline(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'patient_id' => 'required',
            'dentist_id' => 'required',
            'branch_id' => 'required',
            'schedule_id' => 'required', // Ensure schedule is valid
            'proc_id' => 'required', // Ensure schedule is valid
            'appointment_date' => 'required|date', // Ensure date is in valid format
            'preferred_time' => 'required', // Ensure the user selects a time slot
            'status' => 'scheduled',
            'pending' => 'pending',
            'is_online' => 'boolean',
        ]);

        // Check for existing appointments to prevent duplicates
        $existingAppointment = Appointment::where('appointment_date', $validatedData['appointment_date'])
        ->where('preferred_time', $validatedData['preferred_time'])
        ->where('dentist_id', $validatedData['dentist_id']) // Check for the same dentist
        ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['error' => 'This appointment slot is already taken for this dentist.']);
        }

        $patientAppointment = Appointment::where('patient_id', $validatedData['patient_id'])
        ->where('appointment_date', $validatedData['appointment_date'])
        ->first();

        if ($patientAppointment) {
            return redirect()->back()->withErrors(['error' => 'This patient already has an appointment on this date.']);
        }

        // Check if the patient already has a pending appointment
        $pendingAppointment = Appointment::where('patient_id', $validatedData['patient_id'])
        ->where('pending', 'Pending') // Check for pending status
        ->first();

        if ($pendingAppointment) {
        return redirect()->back()->withErrors(['error' => 'This patient already has a pending appointment. Please resolve it before scheduling a new one.']);
        }

        // Create the new appointment record
        $appointment = Appointment::create([
            'patient_id' => $validatedData['patient_id'],
            'dentist_id' => $validatedData['dentist_id'],
            'branch_id' => $validatedData['branch_id'],
            'schedule_id' => $validatedData['schedule_id'], // Store the selected schedule
            'proc_id' => $validatedData['proc_id'], // Store the selected schedule
            'appointment_date' => $validatedData['appointment_date'],
            'preferred_time' => $validatedData['preferred_time'], // Store the selected time slot
            'status' => 'scheduled',
            'pending' => 'pending', // Assuming appointments are pending initially
            'is_online' => $validatedData['is_online'],
        ]);
        $patient = Patient::findOrFail($id);

        return redirect()->route('client.overview', compact('patient', 'id'))->with('success', 'Appointment successfully created!');
    }



    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'dentist_id' => 'required',
            'branch_id' => 'required',
            'proc_id' => 'required',
            'schedule_id' => 'required',
            'appointment_date' => 'required|date',
            'preferred_time' => 'required',
            'status' => 'scheduled',
            'pending' => 'pending',
            'is_online' => 'boolean',
        ]);

        Appointment::create($request->all());
        return redirect()->route('appointment.submission')->with('success', 'Appointment created successfully.');
    }

    /**
     * Fetch dentists based on selected branch via AJAX.
     */
    public function getDentists($branch_id)
    {
        $dentists = Dentist::where('branch_id', $branch_id)->get();
        return response()->json(['dentists' => $dentists]);
    }

    /**
     * Retrieve procedures based on branch selection.
     */
    public function getProcedures($branch_id)
    {
        // Assuming procedures are branch-specific; adjust if necessary
        $procedures = Procedure::where('branch_id', $branch_id)->get();
        return response()->json(['procedures' => $procedures]);
    }

    /**
     * Retrieve schedules based on dentist selection.
     */
    public function getSchedules($dentist_id)
    {
        $schedules = Schedule::where('dentist_id', $dentist_id)
            ->whereDoesntHave('appointment', function ($query) {
                $query->where('status', 'Scheduled');
            })
            ->get();

        return response()->json(['schedules' => $schedules]);
    }


    public function approve($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->Pending = 'Approved';
        $appointment->save();

        $patient = $appointment->patient; // Assuming the relationship is defined in the Appointment model
        $patient->next_visit = $appointment->appointment_date; // Set next visit to the appointment date
        $patient->branch_id = $appointment->branch_id;
        $patient->save(); // Save the updated patient record
        
        Notification::route('mail', $appointment->email)->notify(new AppointmentApproved($appointment));

        return redirect()->back()->with('success', 'Appointment approved and email sent.');
    }

    public function decline($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->Pending = 'Declined';
        $appointment->save();

        Notification::route('mail', $appointment->email)->notify(new AppointmentDeclined($appointment));

        return redirect()->back()->with('success', 'Appointment declined and email sent.');
    }

    //Testing sidebar

    public function walkInAppointment(Request $request)
    {
        $now = Carbon::now();
        $currentDate = $now->startOfDay();

        $walkinAppointmentsQuery = Appointment::with(['patient', 'branch', 'dentistSchedule'])
            // ->where('appointment_date', '>=', $currentDate)
            // ->orderBy('appointment_date','ASC')
            ->where('is_archived', 0)
            ->where('is_online', 0);

        // Check for sorting
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            if ($sortOption == 'created_at') {
                $walkinAppointmentsQuery->orderBy('created_at', 'ASC');
            } elseif ($sortOption == 'preferred_time') {
                $walkinAppointmentsQuery->orderBy('preferred_time', 'ASC');
            } elseif ($sortOption == 'appointment_date') {
                $walkinAppointmentsQuery->orderBy('appointment_date', 'ASC');
            } elseif ($sortOption == 'status') {
                $walkinAppointmentsQuery->orderBy('pending', 'ASC');
            } elseif ($sortOption == 'branch') {
                $walkinAppointmentsQuery->orderBy('branch_id', 'ASC');
            }
        } else {
            $walkinAppointmentsQuery->orderBy('created_at', 'ASC');
        }

        $walkin_appointments = $walkinAppointmentsQuery->paginate(10);

        return view('appointment.appointment-walkIn-list', [
            'walkin_appointments' =>  $walkin_appointments,
            'sort' => $request->get('sort')
        ]);
    }

    public function onlineAppointment(Request $request)
    {

        $now = Carbon::now();
        $currentDate = $now->startOfDay();

        $onlineAppointmentsQuery = Appointment::with(['patient', 'branch', 'dentistSchedule'])
            ->where('appointment_date', '>=', $currentDate)
            // ->orderBy('appointment_date','ASC')
            ->where('is_archived', 0)
            ->where('is_online', 1);
            

            if ($request->has('sort')) {
                $sortOption = $request->get('sort');
                if ($sortOption == 'created_at') {
                    $onlineAppointmentsQuery->orderBy('created_at', 'ASC');
                } elseif ($sortOption == 'preferred_time') {
                    $onlineAppointmentsQuery->orderBy('preferred_time', 'ASC');
                } elseif ($sortOption == 'appointment_date') {
                    $onlineAppointmentsQuery->orderBy('appointment_date', 'ASC');
                } elseif ($sortOption == 'status') {
                    $onlineAppointmentsQuery->orderBy('pending', 'ASC');
                } elseif ($sortOption == 'branch') {
                    $onlineAppointmentsQuery->orderBy('branch_id', 'ASC');
                }
            } else {
                $onlineAppointmentsQuery->orderBy('created_at', 'ASC');
            }
            

        $online_appointments = $onlineAppointmentsQuery->paginate(10);

        return view('appointment.appointment-online-list', [
            'online_appointments' => $online_appointments,
            'sort' => $request->get('sort'),
        ]);
    }
}
