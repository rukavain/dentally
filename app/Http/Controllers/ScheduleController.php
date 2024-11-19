<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Dentist;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\DentistSchedule;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = DentistSchedule::with(['dentist', 'branch']);

        // Handle week selection
        $selectedWeek = $request->get('selectedWeek');
        if ($selectedWeek) {
            $weekStart = Carbon::parse($selectedWeek);
        } else {
            $weekStart = now();
        }

        // Ensure we're at the start of the week
        $weekStart = $weekStart->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        // Apply sorting
        switch ($request->get('sortSchedule', 'current_week')) {
            case 'next_week':
                $weekStart = $weekStart->addWeek();
                $weekEnd = $weekEnd->addWeek();
                break;
            case 'dentist_asc':
                $query->join('dentists', 'dentist_schedules.dentist_id', '=', 'dentists.id')
                    ->orderBy('dentists.dentist_first_name', 'asc')
                    ->orderBy('dentists.dentist_last_name', 'asc')
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->select('dentist_schedules.*'); // Ensure we only get schedule fields
                break;
            case 'dentist_desc':
                $query->join('dentists', 'dentist_schedules.dentist_id', '=', 'dentists.id')
                    ->orderBy('dentists.dentist_first_name', 'desc')
                    ->orderBy('dentists.dentist_last_name', 'desc')
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc')
                    ->select('dentist_schedules.*'); // Ensure we only get schedule fields
                break;
            default: // current_week
                $query->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc');
        }

        // Filter by date range
        $query->whereBetween('date', [
            $weekStart->format('Y-m-d'),
            $weekEnd->format('Y-m-d')
        ]);

        // Get the schedules with pagination
        $schedules = $query->paginate(15)->withQueryString();

        return view('content.schedule', [
            'schedules' => $schedules,
            'weekRange' => [
                'start' => $weekStart->format('M d'),
                'end' => $weekEnd->format('M d'),
                'year' => $weekStart->format('Y')
            ]
        ]);
    }

    public function addSchedule()
    {
        $dentists = Dentist::all();
        $branches = Branch::all();

        return view('dentist.form.add-schedule', compact('dentists', 'branches'));
    }

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);

        return view('content.schedule-information', compact('schedule'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'branch_id' => 'required|exists:branches,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'appointment_duration' => 'required|integer|min:15',
            'selected_dates' => 'required|json', // Expecting a JSON array of dates
        ]);

        // Decode the JSON array of selected dates
        $selectedDates = json_decode($request->selected_dates, true);

        // Loop through each selected date
        foreach ($selectedDates as $dateString) {
            $date = Carbon::parse($dateString);
            $startTime = Carbon::parse($dateString . ' ' . $request->start_time);
            $endTime = Carbon::parse($dateString . ' ' . $request->end_time);

            // Check for overlapping schedules
            $overlappingSchedule = DentistSchedule::where('dentist_id', $request->dentist_id)
                ->whereDate('date', $date)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                            ->where('end_time', '>', $startTime);
                    });
                })
                ->exists();

            if ($overlappingSchedule) {
                return back()->withErrors([
                    'start_time' => 'This schedule overlaps with an existing one.',
                    'end_time' => 'This schedule overlaps with an existing one.',
                ])->withInput();
            }

            // Save the schedule for the specific date
            $schedule = DentistSchedule::create([
                'dentist_id' => $request->dentist_id,
                'branch_id' => $request->branch_id,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'appointment_duration' => $request->appointment_duration,
            ]);
        }

        AuditLog::create([
            'action' => 'Create',
            'model_type' => 'New dentist schedule added',
            'model_id' => $schedule->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('schedule')->with('success', 'Schedules added successfully.');
    }

    public function fetchScheduledDates($dentistId)
    {
        // Validate the dentist ID
        if (!$dentistId) {
            return response()->json(['error' => 'Dentist ID is required.'], 400);
        }

        // Fetch scheduled dates for the given dentist
        $scheduledDates = DentistSchedule::where('dentist_id', $dentistId)
            ->select('date', 'start_time', 'end_time')
            ->get();

        // Format the results
        $formattedSchedules = $scheduledDates->map(function ($schedule) {
            return [
                'date' => Carbon::parse($schedule->date)->format('Y-m-d'),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];
        });

        return response()->json($formattedSchedules);
    }

    public function editSchedule($id)
    {
        $schedule = DentistSchedule::with('dentist')->findOrFail($id);

        return view('dentist.form.edit-schedule', compact('schedule'));
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = DentistSchedule::findOrFail($id);

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'appointment_duration' => 'required|integer|min:15',
        ]);

        // Parse the date and time
        $date = Carbon::parse($request->date);
        $startTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $request->end_time);

        // Check for overlapping schedules
        $overlappingSchedule = DentistSchedule::where('dentist_id', $request->dentist_id)
            ->whereDate('date', $date)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        if ($overlappingSchedule) {
            return back()->withErrors([
                'start_time' => 'This schedule overlaps with an existing one.',
                'end_time' => 'This schedule overlaps with an existing one.',
            ])->withInput();
        }

        // Save the schedule if valid
        $schedule->update([
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_duration' => $request->appointment_duration,
        ]);

        // Log the action
        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Dentist Schedule updated',
            'model_id' => $schedule->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);

        return redirect()->route('schedule')->with('success', 'Schedule added successfully.');
    }

    public function deleteSchedule(Request $request, $id)
    {
        // Find the schedule by ID or fail
        $schedule = DentistSchedule::findOrFail($id);

        // Log the action
        AuditLog::create([
            'action' => 'Delete',
            'model_type' => 'Dentist schedule deleted',
            'model_id' => $schedule->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($schedule->toArray()), // Log the request data
        ]);

        // Delete the schedule
        $schedule->delete();

        // Redirect back with a success message
        return redirect()->route('schedule')->with('success', 'Schedule deleted successfully.');
    }
}
