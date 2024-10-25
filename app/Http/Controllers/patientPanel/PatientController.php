<?php

namespace App\Http\Controllers\patientPanel;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Image;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    //orig controller

    public function activePatient(Request $request)
    {
        $activePatientQuery = Patient::where('is_archived', '0');

        // Check if the search term exists
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $activePatientQuery->where(function ($query) use ($searchTerm) {
                $query->where('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('first_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting logic
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            if ($sortOption == 'next_visit') {
                $activePatientQuery->orderBy('next_visit', 'DESC');
            } elseif ($sortOption == 'id') {
                $activePatientQuery->orderBy('id', 'ASC');
            } elseif ($sortOption == 'name') {
                $activePatientQuery->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC');
            } elseif ($sortOption == 'date_added') {
                $activePatientQuery->orderBy('created_at', 'ASC');
            }
        } else {
            $activePatientQuery->orderBy('created_at', 'ASC');
        }

        // Execute the query and get the results with pagination
        $activePatients = $activePatientQuery->paginate(10)->appends($request->except('page'));

        return view('client.patients.active-patients', [
            'activePatients' => $activePatients,
            'search' => $request->get('search'),
            'sort' => $request->get('sort')
        ]);
    }

    public function archivedPatient(Request $request)
    {
        $archivedPatientQuery = Patient::where('is_archived', '1');

        // Check if the search term exists
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $archivedPatientQuery->where(function ($query) use ($searchTerm) {
                $query->where('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('first_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting logic
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            if ($sortOption == 'next_visit') {
                $archivedPatientQuery->orderBy('next_visit', 'DESC');
            } elseif ($sortOption == 'id') {
                $archivedPatientQuery->orderBy('id', 'ASC');
            } elseif ($sortOption == 'name') {
                $archivedPatientQuery->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC');
            } elseif ($sortOption == 'date_added') {
                $archivedPatientQuery->orderBy('created_at', 'ASC');
            }
        } else {
            $archivedPatientQuery->orderBy('created_at', 'ASC');
        }

        // Execute the query and get the results with pagination
        $archivedPatients = $archivedPatientQuery->paginate(10)->appends($request->except('page'));

        return view('client.patients.archived-patients', [
            'archivedPatients' => $archivedPatients,
            'search' => $request->get('search'),
            'sort' => $request->get('sort')
        ]);
    }


    public function addPatient()
    {
        $branches = Branch::all();

        return view('admin.forms.add-patient', compact('branches'));
    }

    public function storePatient(Request $request)
    {
        $today = Carbon::now();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'email' => 'required|string|email|max:255|unique:patients,email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:12',
            'fb_name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'hmo_company' => 'nullable|string|max:255',
            'hmo_number' => 'nullable|string|max:255',
            'hmo_type' => 'nullable|string|max:255',
            'patient_type' => 'required|in:Walk-in,Orthodontics,Insurance',
        ]);

        $hmoCompany = $request->hmo_company === 'other' ? $request->other_hmo_name : $request->hmo_company;

        // Create patient
        $patient = Patient::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'fb_name' => $request->fb_name,
            'next_visit' => $today,
            'branch_id' => $request->branch_id,
            'has_hmo' => $request->has_hmo ? 1 : 0,
            'hmo_company' => $hmoCompany,
            'hmo_number' => $request->hmo_number,
            'hmo_type' => $request->hmo_type,
            'patient_type' => $request->patient_type,
        ]);

        // Create login credentials for the patient in users table
        User::create([
            'username' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'patient_id' => $patient->id, // Link to the patient via foreign key
        ]);

        AuditLog::create([
            'action' => 'Create',
            'model_type' => 'New patient added',
            'model_id' => $patient->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('patient.active')->with('success', 'Patient created successfully');
    }

    public function showPatient($id)
    {
        $patient = Patient::findOrFail($id);
        $branches = Branch::all();

        return view('client.patients.patient-information', compact('patient', 'branches'));
    }

    public function editPatient($id)
    {

        $patient = Patient::findOrFail($id);
        $branches = Branch::all();

        return view('admin.forms.update-patient', compact('patient', 'branches'));
    }

    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|min:3|max:254',
            'last_name' => 'required|min:3|max:254',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'fb_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'next_visit' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Patient information updated',
            'model_id' => $patient->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        $patient->update($validated);
        return redirect()->route('patient.active', compact('patient'))->with('success', 'Patient updated successfully!');
    }

    public function patientContract(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $contractImages = Image::where('patient_id', $id)
            ->where('image_type', 'contract')
            ->get();

        return view('client.patients.patient-contract', compact('patient', 'contractImages'));
    }

    public function patientBackground(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $backgroundImages = Image::where('patient_id', $id)
            ->where('image_type', 'background')
            ->get();


        return view('client.patients.patient-background', compact('patient', 'backgroundImages'));
    }
    public function patientXray(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $xrayImages = Image::where('patient_id', $id)
            ->where('image_type', 'xray')
            ->get();


        return view('client.patients.patient-xray', compact('patient', 'xrayImages'));
    }


    //Archiving Patient Data
    public function archivePatient(Request $request, $id)
    {
        $patient = Patient::find($id);
        $patient->is_archived = 1;
        $patient->archived_at = now();  // Mark patient as archived with current timestamp
        $patient->save();

        Appointment::where('patient_id', $id)
            ->update(['is_archived' => 1, 'archived_at' => now()]); // Assuming you have an archived_at field in appointments

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Archiving patient',
            'model_id' => $patient->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->back()->with('success', 'Patient has been archived.');
    }

    public function restorePatient(Request $request, $id)
    {
        $patient = Patient::find($id);
        $patient->is_archived = 0;
        $patient->archived_at = null;  // Restore patient by nullifying the archived_at field
        $patient->save();

        Appointment::where('patient_id', $id)
            ->update(['is_archived' => 0, 'archived_at' => null]);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Restoring patient',
            'model_id' => $patient->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->back()->with('success', 'Patient has been restored.');
    }
}
