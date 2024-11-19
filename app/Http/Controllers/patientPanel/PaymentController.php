<?php

namespace App\Http\Controllers\patientPanel;

use Carbon\Carbon;
use App\Models\Image;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Validate;
use App\Models\PaymentHistory;
use App\Models\TemporaryPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{

    public function create($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'procedure', 'dentist'])->find($appointmentId);

        if (!$appointment) {
            return redirect()->route('appointments.walkIn')->with('error', 'Appointment not found.');
        }

        $payment = Payment::where('appointment_id', $appointment->id)->first();

        $totalPaid = $payment ? $payment->total_paid : 0;
        $balanceRemaining = $appointment->procedure->price - $totalPaid;

        return view('admin.forms.payment-form', compact('appointment', 'payment', 'totalPaid', 'balanceRemaining'));
        session()->flash('success', 'Payment added successfully!');
    }

    public function showPaymentHistory($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'procedure'])->find($appointmentId);

        $paymentHistory = PaymentHistory::whereHas('payment', function ($query) use ($appointmentId) {
            $query->where('appointment_id', $appointmentId);
        })->get();

        $totalPaid = $paymentHistory->sum('paid_amount');
        $balanceRemaining = $appointment->procedure->price - $totalPaid;

        return view('admin.forms.payment-history', compact('appointment', 'paymentHistory', 'totalPaid', 'balanceRemaining'));
    }

    public function storePartialPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'paid_amount' => 'required|numeric|min:0',
            'password' => 'required|string',
        ]);

        $appointment = Appointment::with(['procedure', 'patient'])->find($request->appointment_id);
        $payment = Payment::where('appointment_id', $appointment->id)->first();

        if (!Hash::check($request->password, $appointment->patient->password)) {
            return response()->json(['success' => false, 'message' => 'Incorrect password. Please try again.']);
        }

        $totalPaid = 0;
        $balanceRemaining = $appointment->procedure->price;
        if ($balanceRemaining <= 0) {
            $status = 'Paid';
        } else {
            $status = 'Pending';
        };

        if ($payment) {
            $totalPaid = $payment->total_paid;
            $balanceRemaining = $payment->balance_remaining;

            if ($request->paid_amount > $balanceRemaining) {
                return redirect()->back()->with('error', 'Payment exceeds the remaining balance of $' . number_format($balanceRemaining, 2));
            }

            $totalPaid += $request->paid_amount;
            $balanceRemaining -= $request->paid_amount;

            if ($balanceRemaining <= 0) {
                $status = 'Paid';
            } else {
                $status = 'Pending';
            }

            $payment->update([
                'total_paid' => $totalPaid,
                'balance_remaining' => $balanceRemaining,
                'status' => $status,
            ]);
        } else {
            if ($request->paid_amount > $balanceRemaining) {
                return redirect()->back()->with('error', 'Payment exceeds the total amount due of $' . number_format($balanceRemaining, 2));
            }

            // Create a new payment record
            $payment = Payment::create([
                'appointment_id' => $request->appointment_id,
                'amount_due' => $appointment->procedure->price,
                'total_paid' => $request->paid_amount,
                'balance_remaining' => $balanceRemaining - $request->paid_amount,
                'status' => ($request->paid_amount >= $appointment->procedure->price) ? 'Paid' : 'Pending',
            ]);
        }

        // (Optional) Create a payment history record for tracking
        PaymentHistory::create([
            'payment_id' => $payment->id,
            'paid_amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks ?? null, // Optional remarks
        ]);

        AuditLog::create([
            'action' => 'Payment',
            'model_type' => 'New payment added',
            'model_id' => $payment->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'changes' => json_encode($request->all()),
        ]);
        // Return a success response
        // return redirect()->route('show.appointment', $appointment->id)
        //                  ->with('success', 'Payment processed successfully!');
        return response()->json(['success' => true]);
    }



    public function paymentList($id)
    {
        $patient = Patient::findOrFail($id);
        $payments = Appointment::where('patient_id', $id)
                                    ->where('pending', 'Approved')
                                    ->with(['procedure', 'dentist', 'payment'])
                                    ->paginate(5);


        $pendingPayments = TemporaryPayment::where('status', 'pending')->count();

        // $payments = $payment->pluck('id');

        return view('client.patients.patient-payment-list', compact('payments', 'pendingPayments', 'patient'));
    }

    public function pendingPayment($id)
    {
        $patient = Patient::findOrFail($id);

        $pendingPayments = TemporaryPayment::where('status', 'pending')
                                            ->with('payment')
                                            ->get();

        return view('client.patients.patient-pending-payment', compact('pendingPayments', 'patient'));
    }

    public function approvePayment(Request $request, $id)
    {
        // Find the pending payment record
        $pendingPayment = TemporaryPayment::findOrFail($id);

        // Retrieve the appointment details
        $appointment = Appointment::with(['procedure', 'patient'])->find($pendingPayment->payment->appointment_id);

        // Move the payment proof to the approved folder
        if ($pendingPayment->payment_proof) {
            $tempPath = public_path('storage/temp_images/' . $pendingPayment->payment_proof);
            $approvedPath = public_path('storage/approved_images/' . basename($pendingPayment->payment_proof));

            // Check if the file exists in the temporary folder
            if (file_exists($tempPath)) {
                // Move the file to the approved folder
                rename($tempPath, $approvedPath);
                // Update the payment proof path in the database
                $pendingPayment->update(['payment_proof' => 'approved_images/' . basename($pendingPayment->payment_proof)]);
            }
        }

        // Check if a payment record already exists for this appointment
        $payment = Payment::where('appointment_id', $appointment->id)->first();

        // If a payment record exists, update it
        if ($payment) {
            $totalPaid = $payment->total_paid + $pendingPayment->paid_amount;
            $balanceRemaining = $payment->balance_remaining - $pendingPayment->paid_amount;

            // Determine the new status based on the balance remaining
            $status = $balanceRemaining <= 0 ? 'Paid' : 'Pending';

            // Update the existing payment record
            $payment->update([
                'total_paid' => $totalPaid,
                'balance_remaining' => $balanceRemaining,
                'status' => $status,
            ]);
        } else {
            // If no payment record exists, create a new one
            $payment = Payment::create([
                'appointment_id' => $appointment->id,
                'amount_due' => $appointment->procedure->price,
                'total_paid' => $pendingPayment->paid_amount,
                'balance_remaining' => $appointment->procedure->price - $pendingPayment->paid_amount,
                'status' => 'Pending', // Initial status for new payment
            ]);
        }

        // Create a new payment history record
        PaymentHistory::create([
            'payment_id' => $payment->id, // Use the ID of the newly created or updated payment
            'paid_amount' => $pendingPayment->paid_amount,
            'payment_method' => $pendingPayment->payment_method,
            'remarks' => $pendingPayment->remarks,
            'payment_proof' => $pendingPayment->payment_proof, // Use the updated path
        ]);

        Image::create([
            'patient_id' => $appointment->patient_id,
            'image_type' => 'proof_of_payment',
            'image_path' => $pendingPayment->payment_proof,
        ]);

        // Delete the temporary payment record after approval
        $pendingPayment->delete();

        return redirect()->route('payments.list', $appointment->patient_id)->with('success', 'Payment approved successfully!');
    }
}
