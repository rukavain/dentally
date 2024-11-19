<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
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
use App\Notifications\NewAppointmentNotification;

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
        $patients = Patient::where('is_archived', 0)->get();
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
        if (is_array($validatedData['proc_id'])) {
            foreach ($validatedData['proc_id'] as $procId) {
                $appointment = Appointment::create([
                    'patient_id' => $validatedData['patient_id'],
                    'dentist_id' => $validatedData['dentist_id'],
                    'branch_id' => $validatedData['branch_id'],
                    'schedule_id' => $validatedData['schedule_id'],
                    'proc_id' => $procId,
                    'appointment_date' => $validatedData['appointment_date'],
                    'preferred_time' => $validatedData['preferred_time'],
                    'status' => 'scheduled',
                    'pending' => 'pending',
                    'is_online' => $validatedData['is_online'],
                ]);

                // Send notification to admin and staff users
                $users = User::whereIn('role', ['admin', 'staff', 'dentist'])->get();
                foreach ($users as $user) {
                    $user->notify(new NewAppointmentNotification($appointment));
                }
            }
        } else {
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

            // Send notification to admin and staff users
            $users = User::whereIn('role', ['admin', 'staff', 'dentist'])->get();
            foreach ($users as $user) {
                $user->notify(new NewAppointmentNotification($appointment));
            }
        }

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
        if (is_array($validatedData['proc_id'])) {
            foreach ($validatedData['proc_id'] as $procId) {
                $appointment = Appointment::create([
                    'patient_id' => $validatedData['patient_id'],
                    'dentist_id' => $validatedData['dentist_id'],
                    'branch_id' => $validatedData['branch_id'],
                    'schedule_id' => $validatedData['schedule_id'],
                    'proc_id' => $procId,
                    'appointment_date' => $validatedData['appointment_date'],
                    'preferred_time' => $validatedData['preferred_time'],
                    'status' => 'scheduled',
                    'pending' => 'pending',
                    'is_online' => $validatedData['is_online'],
                ]);

                // Send notification to admin and staff users
                $users = User::whereIn('role', ['admin', 'staff', 'dentist'])->get();
                foreach ($users as $user) {
                    $user->notify(new NewAppointmentNotification($appointment));
                }
            }
        } else {
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

            // Send notification to admin and staff users
            $users = User::whereIn('role', ['admin', 'staff', 'dentist'])->get();
            foreach ($users as $user) {
                $user->notify(new NewAppointmentNotification($appointment));
            }
        }
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
        $schedules = DentistSchedule::where('dentist_id', $dentist_id)
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

        $patient = $appointment->patient;
        $patient->next_visit = $appointment->appointment_date;
        $patient->branch_id = $appointment->branch_id;
        $patient->save();

        $user = User::where('patient_id', $patient->id)->first();
        if($user) {
            $user->notify(new AppointmentApproved($appointment));
        }

        return redirect()->back()->with('success', 'Appointment approved and email sent.');
    }

    public function decline($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->Pending = 'Declined';
        $appointment->save();

        $user = User::where('patient_id', $appointment->patient_id)->first();
        if($user) {
            $user->notify(new AppointmentDeclined($appointment));
        }

        return redirect()->back()->with('success', 'Appointment declined and email sent.');
    }

    //Testing sidebar

    public function walkInAppointment(Request $request)
    {
        $query = Appointment::with(['patient', 'branch'])
                          ->where('is_online', 0);

        // Handle search
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $query->whereHas('patient', function($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get sort direction, default to 'asc' if not specified
        $direction = $request->get('direction', 'asc');

        // Handle sorting
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            switch ($sortOption) {
                case 'patient':
                    $query->join('patients', 'appointments.patient_id', '=', 'patients.id')
                          ->orderBy('patients.last_name', $direction)
                          ->orderBy('patients.first_name', $direction)
                          ->select('appointments.*');
                    break;
                case 'date_submitted':
                    $query->orderBy('created_at', $direction);
                    break;
                case 'appointment_date':
                    $query->orderBy('appointment_date', $direction);
                    break;
                case 'time':
                    $query->orderBy('preferred_time', $direction);
                    break;
                case 'branch':
                    $query->join('branches', 'appointments.branch_id', '=', 'branches.id')
                          ->orderBy('branches.branch_loc', $direction)
                          ->select('appointments.*');
                    break;
                case 'status':
                    $query->orderBy('status', $direction);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $walkin_appointments = $query->paginate(10)->appends($request->except('page'));
        return view('appointment.appointment-walkIn-list', compact('walkin_appointments'));
    }

    public function onlineAppointment(Request $request)
    {
        $query = Appointment::with(['patient', 'branch'])
                          ->where('is_online', 1);

        // Handle search
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $query->whereHas('patient', function($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get sort direction, default to 'asc' if not specified
        $direction = $request->get('direction', 'asc');

        // Handle sorting
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            switch ($sortOption) {
                case 'patient':
                    $query->join('patients', 'appointments.patient_id', '=', 'patients.id')
                          ->orderBy('patients.last_name', $direction)
                          ->orderBy('patients.first_name', $direction)
                          ->select('appointments.*');
                    break;
                case 'date_submitted':
                    $query->orderBy('created_at', $direction);
                    break;
                case 'appointment_date':
                    $query->orderBy('appointment_date', $direction);
                    break;
                case 'time':
                    $query->orderBy('preferred_time', $direction);
                    break;
                case 'branch':
                    $query->join('branches', 'appointments.branch_id', '=', 'branches.id')
                          ->orderBy('branches.branch_loc', $direction)
                          ->select('appointments.*');
                    break;
                case 'status':
                    $query->orderBy('status', $direction);
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $online_appointments = $query->paginate(10)->appends($request->except('page'));
        return view('appointment.appointment-online-list', compact('online_appointments'));
    }
}
