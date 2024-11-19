<?php

namespace App\Http\Controllers\adminPanel;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Staff;
use App\Models\Branch;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\DentistSchedule;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function overview()
    {
        $payments = Payment::with('appointment')->get();

        $totalRevenue = $payments->sum('amount_due');

        $today = Carbon::today();

        $totalPatients = Patient::count();
        $newPatients = Patient::whereDate('created_at', $today)->count();
        $todayPatients = Patient::whereDate('next_visit', $today)->count();

        $todayAppointments = Appointment::whereDate('appointment_date', $today)->count();
        $newAppointments = Appointment::whereDate('created_at', $today)->count();

        $todaySchedule = DentistSchedule::whereDate('date', $today)->count();

        $recentPatients = Patient::orderBy('created_at', 'desc')->take(3)->get();
        $pendingAppointments = Appointment::where('pending', 'Pending')->where('status', '!=', 'Cancelled')
        ->orderBy('created_at', 'desc')->take(3)->get();
        $onlineAppointments = Appointment::where('is_online', '1')->where('status', '!=', 'Cancelled')->orderBy('created_at', 'desc')->take(3)->get();
        return view('admin.contents.overview', compact('payments', 'todayPatients', 'newAppointments', 'todayAppointments', 'totalRevenue', 'todaySchedule', 'recentPatients', 'pendingAppointments', 'onlineAppointments'));
    }


    public function staff(Request $request)
    {
        $query = Staff::with('branch');

        // Handle search
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
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
                case 'id':
                    $query->orderBy('staff.id', $direction);
                    break;
                case 'name':
                    $query->orderBy('staff.last_name', $direction)
                          ->orderBy('staff.first_name', $direction);
                    break;
                case 'branch':
                    $query->select('staff.*')
                          ->join('branches', 'staff.branch_id', '=', 'branches.id')
                          ->orderBy('branches.branch_loc', $direction);
                    break;
                default:
                    $query->orderBy('staff.id', 'asc');
            }
        } else {
            $query->orderBy('staff.id', 'asc');
        }

        $staffs = $query->paginate(10)->appends($request->except('page'));

        return view('admin.contents.staff-overview', compact('staffs'));
    }

    public function dentist(Request $request)
    {
        $query = Dentist::with('branch');

        // Handle search
        if ($request->has('search') && !empty($request->get('search'))) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('dentist_first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('dentist_last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('dentist_specialization', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get sort direction, default to 'asc' if not specified
        $direction = $request->get('direction', 'asc');

        // Handle sorting
        if ($request->has('sort')) {
            $sortOption = $request->get('sort');
            switch ($sortOption) {
                case 'id':
                    $query->orderBy('id', $direction);
                    break;
                case 'name':
                    $query->orderBy('dentist_last_name', $direction)
                          ->orderBy('dentist_first_name', $direction);
                    break;
                case 'specialty':
                    $query->orderBy('dentist_specialization', $direction);
                    break;
                case 'branch':
                    $query->join('branches', 'dentists.branch_id', '=', 'branches.id')
                          ->orderBy('branches.branch_loc', $direction)
                          ->select('dentists.*');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $dentists = $query->paginate(10)->appends($request->except('page'));

        return view('admin.contents.dentist-overview', compact('dentists'));
    }


    public function schedule(Request $request)
    {
        $now = Carbon::now();
        $currentDate = $now->startOfDay();


        $scheduleQuery = DentistSchedule::with(['dentist'])
        ->where('date', '>=', $currentDate);

        if ($request->has('sortSchedule')) {
            $sortOption = $request->get('sortSchedule');
            if ($sortOption == 'date') {
                $scheduleQuery->orderBy('date', 'ASC');
            } elseif ($sortOption == 'start_time') {
                $scheduleQuery->orderBy('start_time', 'ASC');
            } elseif ($sortOption == 'end_time') {
                $scheduleQuery->orderBy('end_time', 'ASC');
            }
        } else {
            $scheduleQuery->orderBy('date', 'ASC');
        }


        $schedules = $scheduleQuery->paginate(10);

        return view('content.schedule', compact('schedules'));
    }

    public function branch()
    {
        $branches = Branch::all();
        return view('admin.branch.branch', compact('branches'));
    }

    public function addBranch()
    {
        $branches = Branch::all();
        return view('admin.branch.add-branch', compact('branches'));
    }

    public function storeBranch(Request $request)
    {
        $request->validate([
            'branch_loc' => 'required|string',
        ]);

        $branch = Branch::create([
            'branch_loc' => $request->branch_loc,
        ]);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'New branch added',
            'model_id' => $branch->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);
        return redirect()->route('branch')->with('success', 'Successfully added branch!');
    }
    public function editBranch($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branch.edit-branch', compact('branch'));
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'branch_loc' => 'required|string',
        ]);

        $branch->update([
            'branch_loc' => $request->branch_loc,
        ]);

        AuditLog::create([
            'action' => 'Update',
            'model_type' => 'Branch information updated',
            'model_id' => $branch->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()), // Log the request data
        ]);
        return redirect()->route('branch')->with('success', 'Successfully updated branch!');
    }

    public function deleteBranch($id)
    {
        $branch = Branch::findOrFail($id);

        $branch->delete();

        AuditLog::create([
            'action' => 'Delete',
            'model_type' => 'Branch deleted',
            'model_id' => $branch->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => null, // Log the request data
        ]);

        return redirect()->route('branch')->with('success', 'Successfully deleted branch!');
    }



    public function salesReport(Request $request)
    {
        // Base query for all calculations
        $baseQuery = PaymentHistory::with([
            'payment.appointment.branch',
            'payment.appointment.procedure'
        ])->whereHas('payment.appointment', function($q) {
            $q->whereNotNull('branch_id');
        });

        // Branch filtering
        if ($request->filled('branch')) {
            $baseQuery->whereHas('payment.appointment', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        }

        // Get all payment histories for calculations
        $allPaymentHistories = $baseQuery->get();

        // Get limited payment histories for display
        $paymentHistories = $baseQuery->clone()->orderBy('created_at', 'desc')->limit(4)->get();

        // Calculate metrics using all payment histories
        $totalRevenue = $allPaymentHistories->sum('paid_amount');
        $transactionCount = $allPaymentHistories->count();
        $averageRevenue = $transactionCount > 0 ? $totalRevenue / $transactionCount : 0;

        // Group data by branch using all payment histories
        $branchData = $allPaymentHistories->groupBy('payment.appointment.branch.branch_loc')
            ->map(function ($histories) {
                return $histories->sum('paid_amount');
            });

        // Prepare comparison data for the chart
        $comparisonData = $branchData->toArray();

        // Weekly comparison using all payment histories
        $thisWeekRevenue = $allPaymentHistories
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->sum('paid_amount');
        $lastWeekRevenue = $allPaymentHistories
            ->where('created_at', '>=', Carbon::now()->subWeek()->startOfWeek())
            ->where('created_at', '<', Carbon::now()->startOfWeek())
            ->sum('paid_amount');

        $weeklyComparisonData = [
            'This Week' => $thisWeekRevenue,
            'Last Week' => $lastWeekRevenue
        ];

        // Monthly revenue data using all payment histories
        $monthlyRevenueData = [];
        $startDate = Carbon::now()->subMonths(5)->startOfMonth(); // Get last 6 months

        // Initialize all months with zero values
        for ($i = 0; $i <= 5; $i++) {
            $monthKey = $startDate->copy()->addMonths($i)->format('Y-m'); // Store as YYYY-MM for sorting
            $monthlyRevenueData[$monthKey] = [
                'display' => $startDate->copy()->addMonths($i)->format('M Y'),
                'amount' => 0
            ];
        }

        // Fill in actual values using all payment histories
        foreach ($allPaymentHistories as $history) {
            $date = Carbon::parse($history->created_at);
            if ($date >= $startDate) {
                $monthKey = $date->format('Y-m');
                if (isset($monthlyRevenueData[$monthKey])) {
                    $monthlyRevenueData[$monthKey]['amount'] += $history->paid_amount;
                }
            }
        }

        // Sort by date (chronologically)
        ksort($monthlyRevenueData);

        // Transform for view
        $monthlyRevenueData = collect($monthlyRevenueData)->mapWithKeys(function ($data, $key) {
            return [$data['display'] => $data['amount']];
        })->toArray();

        // Daily revenue data for the past 7 days
        $dailyRevenueData = [];
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // Get last 7 days including today

        // Initialize all days with zero values
        for ($i = 0; $i <= 6; $i++) {
            $dateKey = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dailyRevenueData[$dateKey] = [
                'display' => $startDate->copy()->addDays($i)->format('D, M d'),
                'amount' => 0
            ];
        }

        // Fill in actual values
        foreach ($allPaymentHistories as $history) {
            $date = Carbon::parse($history->created_at)->format('Y-m-d');
            if (isset($dailyRevenueData[$date])) {
                $dailyRevenueData[$date]['amount'] += $history->paid_amount;
            }
        }

        // Transform for view
        $dailyRevenueData = collect($dailyRevenueData)->mapWithKeys(function ($data, $key) {
            return [$data['display'] => $data['amount']];
        })->toArray();

        // Get procedures done today
        $todayProcedures = PaymentHistory::with([
            'payment.appointment.branch',
            'payment.appointment.procedure'
        ])
        ->whereHas('payment.appointment', function($q) {
            $q->whereNotNull('branch_id');
        })
        ->whereDate('created_at', Carbon::today())
        ->when($request->filled('branch'), function($query) use ($request) {
            $query->whereHas('payment.appointment', function($q) use ($request) {
                $q->where('branch_id', $request->branch);
            });
        })
        ->get()
        ->map(function ($history) {
            return [
                'procedure' => $history->payment->appointment->procedure->name,
                'count' => 1,
                'total_amount' => $history->paid_amount
            ];
        })
        ->groupBy('procedure')
        ->map(function ($group) {
            return [
                'procedure' => $group->first()['procedure'],
                'count' => $group->count(),
                'total_amount' => $group->sum('total_amount')
            ];
        })
        ->values();

        // Get all branches for the dropdown
        $branches = Branch::all();

        return view('admin.contents.sales-report', compact(
            'paymentHistories',
            'comparisonData',
            'weeklyComparisonData',
            'monthlyRevenueData',
            'dailyRevenueData',
            'todayProcedures',
            'branches',
            'totalRevenue'
        ));
    }


    //Testing for AuditLog

    public function viewAuditLogs()
    {
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->paginate(20);

        foreach ($auditLogs as $auditLog) {
            $decodedChanges = json_decode($auditLog->changes, true); // Decode JSON to associative array

            // Check if decoding was successful and is an array
            if (is_array($decodedChanges)) {
                $auditLog->changes = $decodedChanges; // Assign decoded changes back to the audit log
            } else {
                $auditLog->changes = []; // Assign an empty array if decoding failed
            }
        }

        return view('audit.logs', compact('auditLogs'));
    }



}
