<?php

namespace App\Http\Controllers\staffPanel;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\AuditLog;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DentistSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function overview()
    {
        $today = Carbon::today();

        $totalPatients = Patient::count();
        $newPatients = Patient::whereDate('created_at', $today)->count();
        $todayPatients = Patient::whereDate('next_visit', $today)->count();

        $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();
        $newAppointments = Appointment::whereDate('created_at', $today)->count();

        $todaySchedule = DentistSchedule::whereDate('date', $today)->count();

        $recentPatients = Patient::orderBy('created_at', 'desc')->take(3)->get();
        $pendingAppointments = Appointment::where('pending', 'Pending')->orderBy('created_at', 'desc')->take(3)->get();
        $onlineAppointments = Appointment::where('is_online', '1')->orderBy('created_at', 'desc')->take(3)->get();
        return view('admin.contents.overview', compact( 'todayPatients', 'newAppointments', 'todayAppointments', 'todaySchedule', 'recentPatients', 'pendingAppointments', 'onlineAppointments'));
    }



    public function addStaff()
    {
        $branches = Branch::all();
        return view('admin.forms.add-staff', compact('branches'));
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:15',
            'gender' => 'nullable|string',
            'fb_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $staff = Staff::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'fb_name' => $request->fb_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
        ]);

        User::create([
            'username' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'staff_id' => $staff->id, // Link to the patient via foreign key
        ]);

        AuditLog::create([
            'action' => 'Create',
            'model_type' => 'New staff added',
            'model_id' => $staff->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);
        return redirect()->route('staff')->with('success', 'Staff created successfully');
    }

    public function editStaff($id)
    {

        $staff = Staff::findOrFail($id);

        return view('admin.forms.update-staff', compact('staff'));
    }
    public function updateStaff(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'fb_name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $staff->update($validated);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Staff information updated',
            'model_id' => $staff->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);
        return redirect()->route('staff', compact('staff'))->with('success', 'Staff updated successfully!');
    }

    public function showStaff($id)
    {
        $staff = Staff::findOrFail($id);
        // $schedules = staffSchedule::with('staff_schedules')->get($id);

        // $staff = staff::with('staffSchedule')->findOrFail($id);

        return view('staff.contents.staff-information', compact('staff'));
    }
}
