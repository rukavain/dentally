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
        $pendingAppointments = Appointment::where('pending', 'Pending')->orderBy('created_at', 'desc')->take(3)->get();
        $onlineAppointments = Appointment::where('is_online', '1')->orderBy('created_at', 'desc')->take(3)->get();
        return view('admin.contents.overview', compact('payments', 'todayPatients', 'newAppointments', 'todayAppointments', 'totalRevenue', 'todaySchedule', 'recentPatients', 'pendingAppointments', 'onlineAppointments'));
    }


    public function staff()
    {
        $staffs = Staff::with('branch')->get();

        return view('admin.contents.staff-overview', compact('staffs'));
    }
    public function dentist()
    {

        $dentists = Dentist::with('branch')->get();
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

   

    public function salesReport()
    {
        $paymentHistories = PaymentHistory::with('payment')->orderBy('created_at','Desc')->get();
        
        $totalRevenue = $paymentHistories->sum('paid_amount');
        $transactionCount = $paymentHistories->count();
        $averageRevenue = $transactionCount > 0 ? $totalRevenue / $transactionCount : 0;
        $comparisonData = [
            'Total' => $totalRevenue,
            'Average' => $averageRevenue
        ];

        $todayRevenue = $paymentHistories->where('created_at', '>=', Carbon::today())->sum('paid_amount');
        $yesterdayRevenue = $paymentHistories->where('created_at', '>=', Carbon::yesterday())->where('created_at', '<', Carbon::today())->sum('paid_amount');
        $dailyComparisonData = [
            'Today' => $todayRevenue,
            'Yesterday' => $yesterdayRevenue
        ];
        
        $monthlyRevenueData = [];
        foreach ($paymentHistories as $history) {
            $month = Carbon::parse($history->created_at)->format('Y-m'); 
            $monthlyRevenueData[$month] = ($monthlyRevenueData[$month] ?? 0) + $history->paid_amount;
        }

        
        $frequentlyPerformedProcedures = $paymentHistories->groupBy('payment_id')->map(function ($group) {
            $payment = $group->first();
            return [
                'procedure' => $payment->payment->appointment->procedure->name,
                'count' => $group->count(),
                'total_amount' => $group->sum('paid_amount'),
            ];
        })->sortByDesc('count')->take(3);

        return view('admin.contents.sales-report', compact('paymentHistories', 'totalRevenue', 'averageRevenue', 'dailyComparisonData', 'comparisonData', 'monthlyRevenueData', 'frequentlyPerformedProcedures'));
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
